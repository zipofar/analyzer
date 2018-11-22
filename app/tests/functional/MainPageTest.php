<?php

class MainPageTestTest extends TestCase
{
    public function test_RootPageIsOk()
    {
        $this->call('GET', '/');
        $this->assertContains('Page speed optimization', $this->response->getContent());
    }
}