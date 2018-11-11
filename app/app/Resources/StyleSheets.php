<?php

namespace App\Resources;


class StyleSheets extends Tag
{
    protected $pattern = '/<link (?P<attr>.*?)\/{0,1}>/mi';
    protected $urlKey = 'href';
}