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

    public function __construct($resource)
    {
        $this->uid = uniqid();
        $this->resource = $resource;
        $this->parseTag();
        $this->setLocation();
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

    abstract public function parseTag();
}