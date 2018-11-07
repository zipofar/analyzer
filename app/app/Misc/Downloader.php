<?php

namespace App\Misc;

use GuzzleHttp\Client;

class Downloader
{
    protected $url;
    protected $client;
    protected $response;
    protected $method;
    protected $domain;
    protected $code;
    protected $contentType;
    protected $id;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function download()
    {
        $this->url = $url;

        $this->response = $response = $this->client->request(
            'GET',
            $url,
            [
                'sink' => __DIR__.'/../../storage/sites/z',
            ]
        );
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
