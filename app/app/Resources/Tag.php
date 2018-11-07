<?php

namespace App\Resources;


use App\Misc\Parser;

abstract class Tag
{
    const INTERNAL = 'int';
    const EXTERNAL = 'ext';
    const INLINE = 'inline';

    const METHOD_HTTP = 'http';
    const METHOD_HTTPS = 'https';

    private $uid;
    private $location = '';
    protected $resource = '';
    protected $attr = [];
    protected $body = '';
    protected $url = '';
    private $httpMethod = '';
    private $domain = '';
    protected $page;

    public function __construct($resource, \App\Resources\Html $html)
    {
        $this->uid = uniqid();
        $this->resource = $resource;
        $this->page = $html;
        $this->parseTag($this->getPattern());
        $this->defineFqdnUrl($this->getUrlKey());
        $this->defineLocation();
    }

    public function setHttpMethod($method)
    {
        if ($method === self::METHOD_HTTP || $method === self::METHOD_HTTPS) {
            $this->httpMethod = $method;
        } else {
            throw new \Exception('Http method undefined');
        }
    }

    public function setLocation($location)
    {
        if ($location === self::INTERNAL
            || $location === self::EXTERNAL
            || $location === self::INLINE
        ) {
            $this->location = $location;
        } else {
            throw new \Exception('Location undefined');
        }
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function parseTag($pattern)
    {
        if ($pattern === null) {
            return;
        }
        preg_match($pattern, $this->resource, $matches);
        $this->body = $matches['body'];
        $attributes = $matches['attr'];

        if (!empty($attributes)) {
            $arrAttr = explode(' ', ltrim($attributes));
            $newAttr = array_reduce($arrAttr, function ($acc, $item) {
                $keyVal = explode('=', $item);
                return array_merge($acc, [$keyVal[0] => trim($keyVal[1], '"\'')]);
            }, []);
            $this->attr = $newAttr;
        }
    }

    public function defineFqdnUrl($urlKey)
    {
        if (empty($urlKey) || !isset($this->attr[$urlKey])) {
            return;
        }
        $originalSrc = $this->attr[$urlKey] ?? '';
        $firstLetter = trim($originalSrc)[0];
        if ($firstLetter === '/') {
            $this->url = $this->page->httpMethod.'://'.$this->page->domain.$originalSrc;
            $this->setHttpMethod($this->page->httpMethod);
            $this->setDomain($this->page->domain);
            return;
        }

        $firstFourSymbols = mb_strtolower(array_slice($originalSrc, 0, 4));
        if ($firstFourSymbols === 'http') {
            $this->url = $originalSrc;
            $this->setHttpMethod(Parser::getHttpMethod($originalSrc));
            $this->setDomain(Parser::getDomain($originalSrc));
            return;
        }
    }

    public function defineLocation()
    {
        if ($this->body !== '') {
            $this->setLocation(self::INLINE);
            return;
        }
        if ($this->page->domain === $this->domain) {
            $this->setLocation(self::INTERNAL);
            return;
        }
        $this->setLocation(self::EXTERNAL);
    }

    abstract public function getPattern();
    abstract public function getUrlKey();
}
