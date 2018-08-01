<?php

namespace lizoenn\Components\Http;

use lizoenn\Components\Http\Exception\HttpException;
use lizoenn\Config;

class Http
{
    protected $config;
    public function __construct(Config $config)
    {
        $this->config = $config->get('http');
    }

    /**
     * get请求
     *
     * @param [type] $url
     * @return void
     */
    public function get($url)
    {
        $config = $this->config;
        $options = [CURLOPT_URL => $url] + $config;

        $ch = \curl_init();
        \curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        if ($errstr = \curl_error($ch)) {
            throw new HttpException($errstr);
        }
        //关闭cURL资源，并且释放系统资源
        curl_close($ch);
        return $response;
    }

    /**
     * post请求
     *
     * @param [type] $url
     * @return void
     */
    public function post($url, $data)
    {
        $config = $this->config;
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ] + $config;

        $ch = \curl_init();
        \curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        if ($errstr = \curl_error($ch)) {
            throw new HttpException($errstr);
        }
        //关闭cURL资源，并且释放系统资源
        curl_close($ch);
        return $response;
    }
}
