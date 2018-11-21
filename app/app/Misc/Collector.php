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

        $images = \App\Misc\Parser::getImages($this->html->resource);
        $this->addResources($images, \App\Resources\Images::class);

        $styles = \App\Misc\Parser::getStyles($this->html->resource);
        $this->addResources($styles, \App\Resources\Styles::class);

        $styleSheets = \App\Misc\Parser::getStyleSheets($this->html->resource);
        $this->addResources($styleSheets, \App\Resources\StyleSheets::class);

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

    public function getExternalScripts()
    {
        return array_filter($this->resources, function ($item) {
            $http_code = $item->stats['http_code'] ?? null;
            return $item instanceof \App\Resources\Script
                && $item->location === \App\Resources\Tag::EXTERNAL
                && $http_code == 200;
        });
    }

    public function getInternalScripts()
    {
        return array_filter($this->resources, function ($item) {
            $http_code = $item->stats['http_code'] ?? null;
            return $item instanceof \App\Resources\Script
                && $item->location === \App\Resources\Tag::INTERNAL
                && $http_code == 200;
        });
    }

    public function getInlineScripts()
    {
        return array_filter($this->resources, function ($item) {
            return $item instanceof \App\Resources\Script && $item->location === \App\Resources\Tag::INLINE;
        });
    }

    public function getExternalStyleSheets()
    {
        return array_filter($this->resources, function ($item) {
            $http_code = $item->stats['http_code'] ?? null;
            return $item instanceof \App\Resources\StyleSheets
                && $item->location === \App\Resources\Tag::EXTERNAL
                && $http_code == 200;
        });
    }

    public function getInternalStyleSheets()
    {
        return array_filter($this->resources, function ($item) {
            $http_code = $item->stats['http_code'] ?? null;
            return $item instanceof \App\Resources\StyleSheets
                && $item->location === \App\Resources\Tag::INTERNAL
                && $http_code == 200;
        });
    }


    public function getInlineStyles()
    {
        return array_filter($this->resources, function ($item) {
            return $item instanceof \App\Resources\Styles;
        });
    }

    public function getImages()
    {
        return array_filter($this->resources, function ($item) {
            $http_code = $item->stats['http_code'] ?? null;
            return $item instanceof \App\Resources\Images && $http_code == 200;
        });
    }

}
