<?php

namespace App\Misc;

use GuzzleHttp\Client;

class Downloader
{
    protected $url;
    protected $client;
    public $response;
    public $method;
    public $domain;
    public $code;
    public $contentType;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function download($url)
    {
        $this->url = $url;

        $this->response = $response = $this->client->request('GET', $url,
            [
            'allow_redirects' => [
                'track_redirects' => true
            ]
        ]);

        $this->parseUrl($this->getLastRedirect());
        $this->code = $response->getStatusCode();



        $this->contentType = $response->getHeader('Content-Type')[0] ?? '';

        return $this;
    }

    public function parseUrl($lastRedirect)
    {
        $url = $lastRedirect !== '' ? $lastRedirect : $this->url;

        $method = \App\Misc\Parser::getHttpMethod($url);
        $domain = \App\Misc\Parser::getDomain($url);

        if ($method === null || $domain === null) {
            throw new \Exception('Can not parse url ->'. $url);
        }
    }

    public function getLastRedirect()
    {
        $redirects = $this->response->getHeaderLine('X-Guzzle-Redirect-History');
        $arrRedirects = explode(', ', $redirects);
        $lastRedirect = $arrRedirects[sizeof($arrRedirects) - 1] ?? '';

        return $lastRedirect;
    }
}