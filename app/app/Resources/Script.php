<?php

namespace App\Resources;


class Script extends Tag
{
    public function getPattern()
    {
        return '/<script.*?(?P<attr>.*?)>(?P<body>.*?)<\/script>/mi';
    }

    public function getUrlKey()
    {
        return 'src';
    }

}