<?php

namespace App\Misc;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Psr\Http\ResponseInterface;

class PageDownloader
{
    protected $client;
    protected $response;
    protected $method;
    protected $domain;
    protected $code;
    protected $stats;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function download($url)
    {
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
                    'allow_redirects' => [
                        'max'             => 10,        // allow at most 10 redirects.
                        'track_redirects' => true
                    ],
                ]
            );
        } catch (\Exception $e) {
            throw $e->getPrevious();
        }

        $this->code = $code = $response->getStatusCode();
        if ($code !== 200) {
            throw new \Exception('Response code = '.$code);
        }
        $this->parseUrl($this->stats['url']);
        $this->stats['redirects'] = $this->getRedirects();
        $this->stats['headers'] = $this->response->getHeaders();

        return $this;
    }

    public function getRedirects()
    {
        // http://first-redirect, http://second-redirect, etc...
        $redirects_url = $this->response->getHeaderLine('X-Guzzle-Redirect-History');
        $result['urls'] = explode(' ', $redirects_url);

        // 301, 302, etc...
        $redirects_code = $this->response->getHeaderLine('X-Guzzle-Redirect-Status-History');
        $result['codes'] = explode(' ', $redirects_code);

        return $result;
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
