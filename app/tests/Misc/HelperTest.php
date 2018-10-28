<?php

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function test_isHtml()
    {
        $str1 = 'text/html; charset=utf-8';
        $str2 = 'json; charset=utf-8';
        $str3 = 'text/xml; charset=utf-8';

        $this->assertTrue(\App\Misc\Helper::isHtml($str1));
        $this->assertFalse(\App\Misc\Helper::isHtml($str2));
        $this->assertFalse(\App\Misc\Helper::isHtml($str3));
    }
}
