<?php
/**
 * Created by Lance.
 * Date: 2018/07/04 15:14
 */
class Api extends CI_Controller
{
    private $sign_params = [
        'openId' => 'E0002',
        'timestamp' => 'E0003',
        'sign' => 'E0004'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录
     * @return mixed
     */
    public function login() {
        $this->load->library('WxProxy');
        $this->load->model('loginToken');

        $jsCode = $this->input->post('jsCode');
        if (!$jsCode || $jsCode == null) {
            return $this->returndatamanager->buildReturnData('E0001');
        }

        $wxLoginResult = $this->wxproxy->wxLogin($jsCode);
        $openId = $wxLoginResult->openid;
        $loginToken = $this->loginToken->checkToken($openId);
        if(!$loginToken) {
            $token = guid();
            $this->loginToken->saveToken($openId, $token);
        } else {
            $token = $loginToken->token;
        }

        $data['openId'] = $openId;
        $data['token'] = $token;
        return $this->returndatamanager->buildReturnData('E0000', $data);
    }

    /**
     * 保存用户数据
     * @return mixed
     */
    public function saveUser() {
        // 验证签名
        if(!$this->checkSign()) {
            return false;
        }

        $this->load->model('user');
        $openId = $this->input->post('openId');
        if(!$openId || $openId == null) {
            return $this->returndatamanager->buildReturnData('E0002');
        }
        $user = $this->user->getUserByOpenId($openId);
        if(!$user) {
            $nickname = $this->input->post('nickname');
            $gender = $this->input->post('gender');
            $avatar = $this->input->post('avatar');
            $this->user->save($openId, $nickname, $gender, $avatar);
        }
        return $this->returndatamanager->buildReturnData('E0000');
    }

    /**
     * 获取美食
     * @param $provinceId //省份
     * @param $page       //页码
     * @return mixed
     */
    public function foods($provinceId, $page) {
        // 验证签名
        if(!$this->checkSign()) {
            return false;
        }

        $this->load->model('food');
        $page != null ? $page : 1;
        if(!$provinceId || $provinceId == null) {
            return $this->returndatamanager->buildReturnData('E0000');
        }

        $foodList = $this->food->getFoodsByPage($provinceId, $page);
        $data['foodList'] = $this->returndatamanager->buildFoodsReturnData($foodList);
        return $this->returndatamanager->buildReturnData('E0000', $data);
    }

    /**
     * 提交并生成结果
     * @return mixed
     */
    public function submit() {
        // 验证签名
        if(!$this->checkSign()) {
            return false;
        }

        $this->load->model('food');
        $this->load->model('user');
        $this->load->model('ana');
        $openId = $this->input->post('openId');
        if(!$openId || $openId == null) {
            return $this->returndatamanager->buildReturnData('E0002');
        }
        $user = $this->user->getUserByOpenId($openId);
        if(!$user) {
            return $this->returndatamanager->buildReturnData('E0006');
        }

        $foodIds = $this->input->post('foodIds');
        if(!$foodIds) {
            return $this->returndatamanager->buildReturnData('E0007');
        }
        $foodIdArray = explode(',', $foodIds);
        // 更新美食的品尝人数
        foreach ($foodIdArray as $foodId) {
            $food = $this->food->getFoodById($foodId);
            $food->eat_num += 1;
            $this->food->updateFood($food);
        }
        // 更新用户品尝美食的数量
        $user->taste_num = count($foodIdArray);
        $this->user->updateUser($user);
        // 计算超过吃友的百分比
        $userCount = $this->user->getUserCount();
        $surpassCount = $this->user->getSurpassCount($user->taste_num);
        $surpassPercent = round(((float)$surpassCount/(float)$userCount)*100, 2);
        // 获取随机语录
        $ana = $this->ana->getRandomAna();
        // 返回结果
        $data['surpassPercent'] = $surpassPercent;
        $data['ana'] = $ana->content;
        $this->returndatamanager->buildReturnData('E0000', $data);
    }

    /**
     * 验证签名
     * @return bool
     */
    private function checkSign() {
        $this->load->model('loginToken');
        // 检查参数
        $checkParamsCode = $this->checkBaseParams();
        if ($checkParamsCode != 'E0000') {
            $this->returndatamanager->buildReturnData($checkParamsCode);
            return false;
        }
        // 检查签名
        $openId = $this->input->post('openId');
        $timestamp = $this->input->post('timestamp');
        $sign = $this->input->post('sign');
        $token = $this->loginToken->checkToken($openId)->token;
        $paramStr = 'openId='. $openId . '&timestamp=' . $timestamp . '&token=' . $token;
        $newSign = strtoupper(md5($paramStr));
        if($newSign !== $sign) {
            $this->returndatamanager->buildReturnData('E0005');
            return false;
        }
        return true;
    }

    /**
     * 检查参数是否存在
     */
    private function checkBaseParams()
    {
        // 返回编码
        $return = 'E0000';
        // 获取签名参数
        foreach ($this->sign_params as $key => $value) {
            if (!$this->input->post($key)) {
                return $value;
                break;
            }
        }
        return $return;
    }

}