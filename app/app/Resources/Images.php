<?php

namespace App\Resources;


class Images extends Tag
{
    protected $pattern = '/<img (?P<attr>.*?)\/{0,1}>/mi';
    protected $urlKey = 'src';
}