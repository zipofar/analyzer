<?php

namespace App\Misc;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Psr\Http\ResponseInterface;

class PageDownloader
{
    protected $url;
    protected $client;
    protected $response;
    protected $method;
    protected $domain;
    protected $code;
    protected $id;
    protected $stats;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function download($url)
    {
        $this->url = $url;
        $this->id = md5($url);
        try {
            $this->response = $response = $this->client->request('GET', $url,
                [
                    'on_headers' => function ($response) {
                        $contentType = $response->getHeaderLine('Content-Type');
                        if (\App\Misc\Helper::isHtml($contentType) === false) {
                            throw new \Exception ('Not HTML type');
                        }

                        $contentLength = $response->getHeaderLine('Content-Length');
                        if ($contentLength > 5000000) {
                            throw new \Exception ('The file is too big');
                        }
                    },
                    'on_stats' => function (TransferStats $stats) {
                        $this->stats = $stats->getHandlerStats();
                    },
                    'http_errors' => false,
                ]
            );
        } catch (\Exception $e) {
            throw $e->getPrevious();
        }

        $this->code = $code = $response->getStatusCode();
        if ($code !== 200) {
            throw new \Exception('Response code = '.$code);
        }
        $url = $this->stats['url'];
        $this->parseUrl($url);

        return $this;
    }

    public function parseUrl($url)
    {
        $method = \App\Misc\Parser::getHttpMethod($url);
        $domain = \App\Misc\Parser::getDomain($url);

        if ($method === null || $domain === null) {
            throw new \Exception('Can not parse url ->'. $url);
        }

        $this->domain = $domain;
        $this->method = $method;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
