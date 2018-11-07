<?php

namespace App\Http\Controllers;

use App\Misc\Downloader;
use App\Resources\Html;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Page extends BaseController
{
    protected $response;
    protected $url;


    public function handle(Request $request, \App\Misc\PageDownloader $downloader)
    {
        $url = $request->input('url');

        try {
            $resource = $downloader->download($url);
        } catch (\Exception $e) {
            return var_dump($e->getMessage());
        }

        $downloadedHtmlPage = $resource->response->getBody();
        $html = new Html($downloadedHtmlPage, $resource->method, $resource->domain);

        $collector = new \App\Misc\Collector();
        $collector->setHtml($html);
        $resources = $collector->getResources();
/*
        $analyzer = new \App\Misc\Analyzer($resources);
        $resultOfAnalysis = $analyzer->getAnalysis();
*/
        return 'OK';
    }


}
