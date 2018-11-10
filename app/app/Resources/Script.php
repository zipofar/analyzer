<?php

namespace App\Resources;


class Script extends Tag
{
    protected $pattern = '/<script.*?(?P<attr>.*?)>(?P<body>.*?)<\/script>/mi';
    protected $urlKey = 'src';
}