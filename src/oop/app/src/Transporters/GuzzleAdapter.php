<?php

namespace src\oop\app\src\Transporters;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class GuzzleAdapter implements TransportInterface
{
    /**
     * @param string $url
     * @return string
     * @throws GuzzleException
     */
    public function getContent(string $url): string
    {
        $content = self::DEFAULT_CONTENT;
        try {
            $client = new Client();
            $content = $client->request('GET', $url)->getBody();
        } catch (Exception $e) {
            echo "Error of getting webpage $url content using GuzzleHttp: ",  $e->getMessage(). '<br>';
        }
        return $content;
    }
}