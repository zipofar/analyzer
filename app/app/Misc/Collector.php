<?php

namespace App\Misc;

use App\Resources\FabricResources;

class Collector
{
    protected $html;

    protected $resources = [];

    protected $downloader;

    protected $storagePath = __DIR__.'/../../storage/sites';

    public function __construct(\App\Misc\Downloader $downloader)
    {
        $this->downloader = $downloader;
    }

    public function setHtml(\App\Resources\Html $html)
    {
        $this->html = $html;
    }

    public function getResources()
    {
        $scripts = \App\Misc\Parser::getScripts($this->html->resource);
        $this->addResources($scripts, \App\Resources\Script::class);
/*
        $styles = \App\Misc\Parser::getStyles($html);
        $this->addResources($styles, 'style');

        $stylesheets = \App\Misc\Parser::getStyleSheets($html);
        $this->addResources($stylesheets, 'stylesheet');

        $images = \App\Misc\Parser::getImages($html);
        $this->addResources($images, 'img');
*/
        $this->download();
    }

    protected function addResources(array $resources, $class)
    {
        $newResources = FabricResources::buildResources($resources, $class, $this->html);
        $this->resources = array_merge($this->resources, $newResources);
    }


    /**
     * @resource \App\Resource\Tag
     */
    protected function download()
    {
        $dirPath = $this->storagePath.'/'.$this->html->domain.'/'.$this->html->uid;
        if (mkdir($dirPath, 0777, true) === false) {
            throw new \Exception("Can not create directory {$dirPath}");
        }

        foreach ($this->resources as $resource) {
            $url = $resource->url;
            if (empty($url) === false) {
                $filePath = $dirPath.'/'.$resource->uid;
                $resource->setFilePath($filePath);
                $this->downloader->add($resource);
            }
        }
        $this->downloader->download();
        $this->setStats();
    }

    protected function setStats()
    {
        $stats = $this->downloader->stats;

        foreach ($this->resources as $resource) {
            $uid = $resource->uid;
            if (isset($stats[$uid])) {
                $resource->setStats($stats[$uid]);
            }
        }
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getAllTags()
    {
        return $this->resources;
    }

    public function getExtScripts()
    {
        return array_filter($this->resources, function ($item) {
            return $item instanceof \App\Resources\Script && $item->location === \App\Resources\Tag::EXTERNAL;
        });
    }

    public function getIntScripts()
    {
        return array_filter($this->resources, function ($item) {
            return $item instanceof \App\Resources\Script && $item->location === \App\Resources\Tag::INTERNAL;
        });
    }

    public function getInlineScripts()
    {
        return array_filter($this->resources, function ($item) {
            return $item instanceof \App\Resources\Script && $item->location === \App\Resources\Tag::INLINE;
        });
    }

}
