<?php
/**
 * Created by Lance.
 * Date: 2018/07/04 15:29
 */

class WxProxy
{
    /**
     * 获取openid和session_key
     * @param $jsCode
     * @return mixed
     */
    public function wxLogin($jsCode) {
        $appId = 'wx22e48525433a9d34';
        $appSecret = '3e159be57dfd6d755d53e31f2f8f9108';
        $path = 'https://api.weixin.qq.com/sns/jscode2session?appid='. $appId . '&secret=' . $appSecret
            . '&js_code=' . $jsCode . '&grant_type=authorization_code';
        $result = json_decode(curl_post($path));
        return $result;
    }
}