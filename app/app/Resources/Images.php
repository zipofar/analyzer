<?php

namespace App\Resources;


class Images extends Tag
{
    protected $pattern = '/<img.*?(?P<attr>.*?)>/mi';
    protected $urlKey = 'src';
}