<?php

namespace classes\service;

class CurlService
{
    public static function sendRequest($url, array $parameters)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?' . http_build_query($parameters));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $response = trim(curl_exec($curl));
        curl_close($curl);

        return $response;
    }
}