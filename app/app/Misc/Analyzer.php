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
        $this->result['images'] = $this->getImageAnalyzes();
        $this->result['css'] = $this->getCssAnalyzes();

        return $this->result;
    }

    protected function getPageAnalyzes()
    {
        $result = [];
        $html = $this->collector->getHtml();
        $result['response_time'] = $html->stats['starttransfer_time'] * 1000; // Milli seconds
        $result['count_redirects'] = sizeof($html->stats['redirects']['urls']);

        $tagsWithBadRequest = $this->getTagsWithBadRequests();
        $result['count_bad_requests'] = sizeof($tagsWithBadRequest);
        $result['url_bad_requests'] = $this->getUrl($tagsWithBadRequest);
        return $result;
    }

    protected function getScriptAnalyzes()
    {
        $result = [];

        $internal = $this->collector->getInternalScripts();
        $external = $this->collector->getExternalScripts();
        $inline = $this->collector->getInlineScripts();

        $result['int']['size'] = $this->getSumOfSizes($internal);
        $result['ext']['size'] = $this->getSumOfSizes($external);
        $result['total_size'] = $result['int']['size'] + $result['ext']['size'];

        $result['int']['count'] = sizeof($internal);
        $result['ext']['count'] = sizeof($external);
        $result['inline']['count'] = sizeof($inline);
        $result['total_count'] = $result['int']['count'] + $result['ext']['count'];

        $result['int']['url'] = $this->getUrl($internal);
        $result['ext']['url'] = $this->getUrl($external);

        $result['inline']['body'] = $this->getBody($inline);
        return $result;
    }

    protected function getImageAnalyzes()
    {
        $result = [];
        $images = $this->collector->getImages();
        $result['total_size'] = $this->getSumOfSizes($images);
        $result['total_count'] = sizeof($images);

        $result['img'] = array_map(function (\App\Resources\Tag $item) {
            return [
                'url' => $item->url,
                'size' => filesize($item->filePath),
            ];
        }, $images);

        return $result;
    }

    protected function getCssAnalyzes()
    {
        $result = [];

        $internal = $this->collector->getInternalStyleSheets();
        $external = $this->collector->getExternalStyleSheets();
        $inline = $this->collector->getInlineStyles();

        $result['int']['size'] = $this->getSumOfSizes($internal);
        $result['ext']['size'] = $this->getSumOfSizes($external);
        $result['total_size'] = $result['int']['size'] + $result['ext']['size'];

        $result['int']['count'] = sizeof($internal);
        $result['ext']['count'] = sizeof($external);
        $result['inline']['count'] = sizeof($inline);
        $result['total_count'] = $result['int']['count'] + $result['ext']['count'];


        $result['int']['url'] = $this->getUrl($internal);
        $result['ext']['url'] = $this->getUrl($external);

        $result['inline']['body'] = $this->getBody($inline);
        return $result;
    }

    protected function getSumOfSizes(array $resources)
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

    protected function getTagsWithBadRequests()
    {
        $tags = $this->collector->getAllTags();
        return array_filter($tags, function (\App\Resources\Tag $item) {
            $http_code = $item->stats['http_code'] ?? null;
            return $http_code !== 200 && !empty($http_code);
        });
    }

}