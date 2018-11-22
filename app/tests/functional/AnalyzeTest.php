<?php

class AnalyzeTest extends TestCase
{
    public function test_Analyze()
    {
        $response = $this->call('POST', '/analyze', ['url' => 'http://nginx_test']);
        $content = $response->getContent();
        $this->assertContains('Count redirects: 0', $content);
        $this->assertContains('Count bad requests: 5', $content);
        $this->assertContains('Count CSS: 2', $content);
        $this->assertContains('Count JS: 2', $content);
        $this->assertContains('Count Images: 7', $content);
        $this->assertContains('JavaScript size: 474357', $content);
        $this->assertContains('External: 126846 / Internal: 347511', $content);
        $this->assertContains('CSS size: 245383', $content);
        $this->assertContains('External: 88227 / Internal: 157156', $content);
        $this->assertContains('Images size: 5517', $content);
        $this->assertContains('a96ae5cccf2cee954045f50f548235ac', $content);
    }
}