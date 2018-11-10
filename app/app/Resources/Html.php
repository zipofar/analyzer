<?php

namespace App\Resources;


class Html extends Tag
{
    protected $pattern = null;
    protected $urlKey = null;

    public function __construct($resource, $httpMethod, $domain)
    {
        parent::__construct($resource, $this);
        $this->setDomain($domain);
        $this->setHttpMethod($httpMethod);
    }
}
