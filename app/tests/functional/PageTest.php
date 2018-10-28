<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PageTest extends TestCase
{
    public function test_IsOk()
    {
        $response = $this->call('POST', '/analyze', ['url' => 'http://zipofar.ru']);
        $this->assertEquals('OK', $this->response->getContent());
    }

    public function test_NotResolvedHost()
    {
        $response = $this->call('POST', '/analyze', ['url' => 'https://zipofar1.ru']);
        $this->assertEquals('Resource not found', $this->response->getContent());
    }

    public function test_NotFound()
    {
        $response = $this->call('POST', '/analyze', ['url' => 'https://zipofar.ru/doku2.php']);
        $this->assertEquals('Resource not found', $this->response->getContent());
    }

    public function test_NotHtml()
    {
        $response = $this->call('POST', '/analyze', ['url' => 'https://zipofar.ru/lib/tpl/dokuwiki/images/logo.png']);
        $this->assertEquals('Not HTML resource', $this->response->getContent());
    }
}
