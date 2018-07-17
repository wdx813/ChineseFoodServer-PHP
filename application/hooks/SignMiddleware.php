<?php
/**
 * Created by Lance.
 * Date: 2018/07/05 13:55
 */

class SignMiddleware
{
    private $CI;

    private $sign_params = [
        'openId' => 'E0002',
        'timestamp' => 'E0003',
        'sign' => 'E0004'
    ];

    private $no_sign_proto = [
        '/_API/login'
    ];

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model('loginToken');
    }

    /**
     * 验证签名
     * @return bool
     */
    public function checkSign() {
        if(!in_array($_SERVER['REQUEST_URI'], $this->no_sign_proto)) {
            log_message('debug', '执行到这里了。。。。');
            // 检查参数
            $checkParamsCode = $this->checkBaseParams();
            if ($checkParamsCode != 'E0000') {
                return $this->CI->returndatamanager->buildReturnData($checkParamsCode);
            }
            // 检查签名
            $openId = $this->CI->input->post('openId');
            $timestamp = $this->CI->input->post('timestamp');
            $sign = $this->CI->input->post('sign');
            $token = $this->CI->loginToken->checkToken($openId)->token;
            log_message('debug', '前端的sign。。。。'. $sign);
            $paramStr = 'openId='. $openId . '&timestamp=' . $timestamp . '&token=' . $token;
            $newSign = strtoupper(md5($paramStr));
            log_message('debug', 'lance===='.$newSign);
            if($newSign !== $sign) {
                log_message('debug', 'lance====签名验证失败');
                return $this->CI->returndatamanager->buildReturnData('E0005');
            }
        }
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
            if (!$this->CI->input->post($key)) {
                return $value;
                break;
            }
        }
        return $return;
    }
}