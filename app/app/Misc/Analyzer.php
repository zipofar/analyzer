<?php

namespace App\Misc;

use App\Misc\Collector;

class Analyzer
{
    protected $resources;

    public function __construct(Collector $resources)
    {
        $this->resources = $resources;
    }

    public function getAnalysis()
    {
        $esult['avg_response_time'] = $this->getAvgResponseTime();
        $result['scripts']['total_size'] = $this->getTotalSizeResource('script');

        return $result;
    }

    public function getAvgResponseTime()
    {
        $time = 0;
        foreach ($this->resources as $resource) {
            $time += (int) $resource->server['response_time'];
        }
        $avgTime = $time / sizeof($this->resources);
        return $avgTime;
    }

    public function getTotalSizeResource($tagName)
    {
        $size = 0;
        foreach ($this->resources as $resource) {
            if ($resource->tagName === $tagName) {
                $size += $resource->headers['Content-Length'] ?? 0;
            }
        }
        return $size;
    }
}