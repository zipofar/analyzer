<?php

namespace App\Misc;

class Parser
{
    public static function getTagImg($html)
    {
        $pattern = '/<img.*?>/mi';
        preg_match_all($pattern, $html, $matches);
        return $matches[0];
    }

    public static function getTagScript($html)
    {
        $pattern = '/<script.*?><\/script>/mi';
        preg_match_all($pattern, $html, $matches);
        return $matches[0];
    }

    public static function getTagLink($html)
    {
        $pattern = '/<link.*?>/mi';
        preg_match_all($pattern, $html, $matches);
        return $matches[0];
    }

    public static function isStyleSheetTag($tag)
    {
        return mb_stripos($tag, 'rel="stylesheet"') === false ? false : true;
    }

    public static function getHrefFromTag($tag)
    {
        $pattern = '/href="(?P<href>.*?)"/im';
        preg_match($pattern, $tag, $matches);
        return $matches['href'] ?? null;
    }

    public static function getSrcFromTag($tag)
    {
        $pattern = '/src="(?P<src>.*?)"/im';
        preg_match($pattern, $tag, $matches);
        return $matches['src'] ?? null;
    }
}
