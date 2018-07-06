<?php
/**
 * Created by Lance.
 * Date: 2018/07/04 15:11
 */
class ReturnDataManager
{
    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function buildReturnData($code, $data = null) {
        $returnData['code'] = $code;
        if ($code !== 'E0000') {
            $returnData['msg'] = $this->CI->errorconstantsmanager->errorMessageList[$code];
        }
        if ($data) {
            $returnData['data'] = $data;
        }
        $this->CI->output->set_content_type('application/json', 'UTF-8')
            ->set_output(json_encode($returnData));
    }

    public function buildFoodsReturnData($foodList) {
        $data = [];
        foreach ($foodList as $item) {
            $food['id'] = $item->id;
            $food['name'] = $item->name;
            $food['imgUrl'] = $item->img_url;
            $food['provinceId'] = $item->province_id;
            $food['eatNum'] = $item->eat_num;
            array_push($data, $food);
        }
        return $data;
    }
}