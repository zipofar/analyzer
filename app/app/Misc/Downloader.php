<?php

namespace App\Misc;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class Downloader
{
    protected $client;
    protected $promises;
    protected $stats;
    //protected $filePath;
    //protected $url;
    //protected $responseTime;
    //protected $responseCode;
    //protected $contentType;
    //protected $httpMethod;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function add(\App\Resources\Tag $resource)
    {
        $uid = $resource->uid;
        $this->promises[$uid] = $this->client->getAsync($resource->url,
            [
                'sink' => $resource->filePath,
                'on_stats' => function (\GuzzleHttp\TransferStats $stats) use ($uid) {
                    $this->stats[$uid] = $stats->getHandlerStats();
                }
            ]);
    }

    public function download()
    {
        $results = Promise\settle($this->promises)->wait();
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
