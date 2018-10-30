<?php

namespace App\Resources;


class Script extends Tag
{
    public function parseTag()
    {
        $pattern = '/<script.*?(?P<attr>.*?)>(?P<body>.*?)<\/script>/mi';
        preg_match_all($pattern, $this->tag, $matches);
        $attributes = $matches[0]['attr'] ?? '';
        $this->attr = explode(' ', ltrim($attributes));
        $this->body = $matches[0]['body'] ?? '';
    }

    public function setLocation()
    {
        if ($this->body !== '') {
            $this->location = self::INLINE;
            return;
        }
    }
}