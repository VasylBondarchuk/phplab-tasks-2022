<?php

namespace src\oop\app\src\Transporters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7;

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
        } catch (ClientException $e) {
            echo Psr7\Message::toString($e->getRequest());
            echo Psr7\Message::toString($e->getResponse());
        }
        return $content;
    }
}