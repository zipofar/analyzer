<?php

namespace App\Misc;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Psr\Http\ResponseInterface;

class PageDownloader
{
    const NOT_HTML = 1000;
    const FILE_TOO_BIG = 1001;

    protected $client;
    protected $response;
    protected $method;
    protected $domain;
    protected $stats;
    protected $error;

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
                            throw new \Exception ('Not HTML type', self::NOT_HTML);
                        }

                        $contentLength = $response->getHeaderLine('Content-Length');
                        if ($contentLength > 1000000) {
                            throw new \Exception ('The file is too big', self::FILE_TOO_BIG);
                        }
                    },
                    'on_stats' => function (TransferStats $stats) {
                        $this->stats = $stats = $stats->getHandlerStats();
                    },
                    'http_errors' => false,
                    'allow_redirects' => [
                        'max'             => 10,        // allow at most 10 redirects.
                        'track_redirects' => true
                    ],
                ]
            );
        } catch (\Exception $e) {
            $this->errorHandler($e);
            return;
        }

        $this->parseUrl($this->stats['url']);
        $this->stats['redirects'] = $this->getRedirects();
        $this->stats['headers'] = $this->response->getHeaders();
    }

    public function errorHandler($e)
    {
        $context = $e->getHandlerContext();
        if (isset($context['errno'])) {
            if ($context['errno'] == 3) {
                $this->error = 'Not a valid url. Please enter valid URL - example - zipofar.ru';
                return;
            }

            if ($context['errno'] == 6) {
                $this->error = 'Could not resolve host';
                return;
            }
        }

        $previousException = $e->getPrevious();

        if ($previousException !== null) {
            if ($previousException->getCode() === self::NOT_HTML) {
                $this->error = 'This resource is not HTML';
                return;
            }
            if ($previousException->getCode() === self::FILE_TOO_BIG) {
                $this->error = 'This resource bigger then 1 MB';
                return;
            }
        }

        $this->error = 'Something went wrong';
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
