<?php

namespace App\Misc;

use App\Misc\Collector;

class Analyzer
{
    protected $collector;

    protected $result = [];

    public function __construct(Collector $collector)
    {
        $this->collector = $collector;
    }

    public function getAnalyzes()
    {
        $this->result['page'] = $this->getPageAnalyzes();
        $this->result['scripts'] = $this->getScriptAnalyzes();
        //$result['avg_response_time'] = $this->getAvgResponseTime();
        //$result['scripts']['total_size'] = $this->getTotalSizeResource('script');

        return $this->result;
    }

    protected function getPageAnalyzes()
    {
        $result = [];
        $html = $this->collector->getHtml();
        $result['response_time'] = $html->stats['starttransfer_time'] * 1000;
        $result['count_redirects'] = sizeof($html->stats['redirects']['urls']);
        return $result;
    }

    protected function getScriptAnalyzes()
    {
        $result = [];

        $internal = $this->collector->getIntScripts();
        $external = $this->collector->getExtScripts();
        $inline = $this->collector->getInlineScripts();

        $result['int']['size'] = $this->getSize($internal);
        $result['ext']['size'] = $this->getSize($external);

        $result['int']['count'] = sizeof($internal);
        $result['ext']['count'] = sizeof($external);
        $result['inline']['count'] = sizeof($inline);

        $result['total_size'] = $result['int']['size'] + $result['ext']['size'];

        $result['int']['url'] = $this->getUrl($internal);
        $result['ext']['url'] = $this->getUrl($external);

        $result['inline']['body'] = $this->getBody($inline);
        return $result;
    }

    protected function getSize(array $resources)
    {
        return array_reduce($resources, function ($acc, \App\Resources\Tag $item) {
            $fileSize = filesize($item->filePath);
            return $acc + $fileSize;
        }, 0);
    }

    protected function getUrl(array $resources)
    {
        return array_map(function (\App\Resources\Tag $item) {
            return $item->url;
        }, $resources);
    }

    protected function getBody(array $resources)
    {
        return array_map(function (\App\Resources\Tag $item) {
            return $item->body;
        }, $resources);
    }

/*
    public function getAvgResponseTime()
    {
        $time = 0;
        foreach ($this->collector as $resource) {
            $time += (int) $resource->server['response_time'];
        }
        $avgTime = $time / sizeof($this->collector);
        return $avgTime;
    }

    public function getTotalSizeResource($tagName)
    {
        $size = 0;
        foreach ($this->collector as $resource) {
            if ($resource->tagName === $tagName) {
                $size += $resource->headers['Content-Length'] ?? 0;
            }
        }
        return $size;
    }
*/
}