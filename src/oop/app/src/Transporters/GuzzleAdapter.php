<?php

namespace src\oop\app\src\Transporters;

use GuzzleHttp\Client;

class GuzzleAdapter implements TransportInterface
{
    public function getContent(string $url): string
    {
        $client = new Client();
        $html = $client->request('GET', $url);
        return $html->getBody();
    }
}