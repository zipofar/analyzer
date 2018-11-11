<?php

namespace App\Resources;


class Styles extends Tag
{
    protected $pattern = '/<style.*?(?P<attr>.*?)>(?P<body>.*?)<\/style>/mi';
    protected $urlKey = '';
}