<?php

namespace App\Misc;

class Parser
{
    public static function getImages($html)
    {
        $pattern = '/<img.*?>/mi';
        preg_match_all($pattern, $html, $matches);
        $tags = $matches[0];
        return $tags;
    }

    public static function getScripts($html)
    {
        $pattern = '/<script.*?>.*?<\/script>/mi';
        preg_match_all($pattern, $html, $matches);
        $tags = $matches[0];
        return $tags;
    }

    public static function getStyles($html)
    {
        $pattern = '/<style.*?>.*?<\/style>/mi';
        preg_match_all($pattern, $html, $matches);
        $tags = $matches[0];
        return $tags;
    }

    public static function getStyleSheets($html)
    {
        $pattern = '/<link rel="stylesheet".*?>/mi';
        preg_match_all($pattern, $html, $matches);
        $tags = $matches[0];
        return $tags;
    }

/*
    public static function getScripts($html)
    {
        $pattern = '/<script.*?(?P<attr>.*?)>(?P<body>.*?)<\/script>/mi';
        preg_match_all($pattern, $html, $matches);
        $tags = $matches[0];
        $attributes = $matches['attr'];
        $body = $matches['body'];
        $arrTags = array_map(function ($tag, $attr, $body) {
            return ['tag' => $tag, 'attr' => $attr, 'body' => $body];
        }, $tags, $attributes, $body);
        return $arrTags;
    }
*/
    public static function getHttpMethod($url)
    {
        //$pattern = '/(?P<method>https{0,1}):\/\/(?P<domain>.*?)(\/|\?|$)/i';
        $pattern = '/(?P<method>https{0,1}):\/\//i';

        preg_match($pattern, $url, $matches);
        $method = $matches['method'] ?? null;

        return $method;
    }

    public static function getDomain($url)
    {
        $pattern = '/https{0,1}:\/\/(?P<domain>.*?)(\/|\?|$)/i';

        preg_match($pattern, $url, $matches);
        $domain = $matches['domain'] ?? null;

        return $domain;
    }
}
