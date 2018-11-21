<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PageTest extends TestCase
{
    public function test_rootPage()
    {
        $response = $this->call('GET', '/');
        $this->assertContains('Page speed optimization', $this->response->getContent());
    }

    public function test_Analyze()
    {
        $response = $this->call('POST', '/analyze', ['url' => 'http://nginx_test']);
        $this->assertContains('Summary', $this->response->getContent());
    }
}
