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

    public static function getTagName($tag)
    {
        $pattern = '/<(?P<tagname>\w*)(\s|>)/mi';
        preg_match_all($pattern, $tag, $matches);
        if (!isset($matches[0]['tagname'])) {
            throw new \Exception('Can not resolve tag name');
        }
        return $matches[0]['tagname'];
    }

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
