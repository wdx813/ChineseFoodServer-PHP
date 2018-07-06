<?php
/**
 * Created by Lance.
 * Date: 2018/07/05 11:12
 */

/**
 * 获取uuid
 */
if (!function_exists('guid')) {
    function guid() {
        if(function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand(( double )microtime() * 10000);
            $charId = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = substr($charId, 0, 8) . $hyphen . substr($charId, 8, 4) . $hyphen . substr($charId, 12, 4) . $hyphen . substr($charId, 16, 4) . $hyphen . substr($charId, 20, 12);
            return $uuid;
        }
    }
}

/**
 * curl post请求
 */
if (!function_exists('curl_post')) {
    function curl_post($url, $data = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
