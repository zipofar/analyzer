<?php

namespace App\Misc;

class Helper
{
    public static function isHtml($str)
    {
        $pattern = 'text/html';
        return stripos($str, $pattern) === false ? false : true;
    }

    public static function removeAllBreakLines($string)
    {
        return str_replace(array("\n\r", "\n", "\r"), '', $string);
    }

    public static function removeUnusedSpaces($string)
    {
        return preg_replace('/\s+/im',' ',$string);
    }

    public static function clearUnusedSymbols($string)
    {
        $string = self::removeAllBreakLines($string);
        $string = self::removeUnusedSpaces($string);
        return $string;
    }
}
