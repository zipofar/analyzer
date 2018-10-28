<?php

namespace App\Misc;

class Helper
{
    public static function isHtml($str)
    {
        $pattern = 'text/html';
        return stripos($str, $pattern) === false ? false : true;
    }
}
