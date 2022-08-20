<?php

namespace src\oop\app\src\Transporters;

/**
 *
 */
class CurlStrategy implements TransportInterface
{
    public function getContent(string $url): string
    {
        if (!function_exists('curl_init')){
            die('cURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}