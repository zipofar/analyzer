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
        //$result['avg_response_time'] = $this->getAvgResponseTime();
        //$result['scripts']['total_size'] = $this->getTotalSizeResource('script');

        return $this->result;
    }

    protected function getPageAnalyzes()
    {
        $result = [];
        $html = $this->collector->getHtml();
        $result['response_time'] = $html->stats['starttransfer_time'] * 1000;
var_dump($html->stats);
        return $result;
    }

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
}