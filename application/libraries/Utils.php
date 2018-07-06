<?php
/**
 * Created by Lance.
 * Date: 2018/07/04 14:04
 */

class Utils
{
    /**
     * 获取uuid
     * @return string
     */
    public function guid() {
        if(function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand(( double )microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
            return $uuid;
        }
    }

    /**
     * curl post请求
     * @param $url
     * @param $data
     * @return mixed
     */
    public function curl_post($url, $data = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 0);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);
        return $result;
    }
}