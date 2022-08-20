<?php

namespace src\oop\app\src\Transporters;

use Exception;

/**
 *
 */
class CurlStrategy implements TransportInterface
{
    /**
     * @param string $url
     * @return string
     */
    public function getContent(string $url): string
    {
        $content = self::DEFAULT_CONTENT;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $content = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            echo "Error of getting webpage $url content using cURL: ",  $e->getMessage(). '<br>';
        }
        return $content;
    }
}