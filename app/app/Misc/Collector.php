<?php

namespace App\Misc;

use App\Resources\FabricResources;

class Collector
{
    protected $html;

    protected $resources = [];

    public function __construct($html)
    {
        $this->html = $html;
        $this->initialize();
    }

    public function initialize()
    {
        $html = $this->html;

        $scripts = \App\Misc\Parser::getScripts($html);
        $this->addResources($scripts, \App\Resources\Script::class);
/*
        $styles = \App\Misc\Parser::getStyles($html);
        $this->addResources($styles, 'style');

        $stylesheets = \App\Misc\Parser::getStyleSheets($html);
        $this->addResources($stylesheets, 'stylesheet');

        $images = \App\Misc\Parser::getImages($html);
        $this->addResources($images, 'img');
*/
    }

    protected function addResources(array $resources, $class)
    {
        $newResources = FabricResources::buildResources($resources, $class);
        $this->resources = array_merge($this->resources, $newResources);
    }
}