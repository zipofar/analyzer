<?php

namespace App\Resources;


class Html extends Tag
{
    public function __construct($resource, $httpMethod, $domain)
    {
        parent::__construct($resource, $this);
        $this->setDomain($domain);
        $this->setHttpMethod($httpMethod);
    }

    public function getPattern()
    {
        return null;
    }

    public function getUrlKey()
    {
        return null;
    }
}