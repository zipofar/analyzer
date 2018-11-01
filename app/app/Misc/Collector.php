<?php

namespace App\Misc;

use App\Resources\FabricResources;

class Collector
{
    protected $page;

    protected $resources = [];

    public function __construct($page)
    {
        $this->page = $page;
        $this->initialize();
    }

    public function initialize()
    {
        $page = $this->page;

        $scripts = \App\Misc\Parser::getScripts($page->resource);
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
        $newResources = FabricResources::buildResources($resources, $class, $this->page);
        $this->resources = array_merge($this->resources, $newResources);
    }

    public function getResources()
    {
        return $this->resources;
    }
}