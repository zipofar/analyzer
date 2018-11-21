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

    protected $uid;
    protected $location = '';
    protected $resource = '';
    protected $attr = [];
    protected $body = '';
    protected $url = '';
    protected $httpMethod = '';
    protected $domain = '';
    protected $html;
    protected $stats = [];

    protected $pattern = null;
    protected $urlKey = null;

    protected $filePath = '';

    public function __construct($resource, \App\Resources\Html $html)
    {
        $this->uid = mb_substr(md5($resource), 0, 10).time();
        $this->resource = $resource;
        $this->html = $html;
        $this->parseTag($this->pattern);
        $this->defineFqdnUrl($this->urlKey);
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

    public function setFilePath($path)
    {
        $this->filePath = $path;
    }

    public function setStats(array $stats)
    {
        $this->stats = array_merge($this->stats, $stats);
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
        $this->body = $matches['body'] ?? '';
        $attributes = $matches['attr'];

        if (!empty($attributes)) {
            $arrAttr = explode(' ', ltrim($attributes));
            $newAttr = array_reduce($arrAttr, function ($acc, $item) {
                $keyVal = explode('="', $item);
                $key = $keyVal[0];
                $value = $keyVal[1] ?? '';
                $value = trim($value, '"\'');
                return array_merge($acc, [$key => $value]);
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
        $originalSrc = trim($originalSrc);
        if ($this->beginStrWith($originalSrc, '//')) {
            $this->url = $this->html->httpMethod.':'.$originalSrc;
            $this->setHttpMethod($this->html->httpMethod);
            $this->setDomain(Parser::getDomain($this->url));
            return;
        }

        if ($this->beginStrWith($originalSrc, '/')) {
            $this->url = $this->html->httpMethod.'://'.$this->html->domain.$originalSrc;
            $this->setHttpMethod($this->html->httpMethod);
            $this->setDomain($this->html->domain);
            return;
        }

        if ($this->beginStrWith($originalSrc, 'http')) {
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
        if ($this->html->domain === $this->domain) {
            $this->setLocation(self::INTERNAL);
            return;
        }
        $this->setLocation(self::EXTERNAL);
    }

    protected function beginStrWith($haystack, $needle)
    {
        $haystack = mb_strtolower($haystack);
        $needle = mb_strtolower($needle);
        return mb_stripos($haystack, $needle) === 0 ? true : false;
    }
}
