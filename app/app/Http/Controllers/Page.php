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


    public function handle(Request $request, Downloader $downloader)
    {
        $url = $request->input('url');


        try {
            $resource = $downloader->download($url);
        } catch (\Exception $e) {
            return 'Resource not found';
        }

        if ($resource->code !== 200) {
            return 'Not available resource';
        }

        if (!\App\Misc\Helper::isHtml($resource->contentType)) {
            return 'Not HTML resource';
        }

        $body = $resource->response->getBody();
        $page = new Html($body, $resource->method, $resource->domain);



        $collector = new \App\Misc\Collector($page);
        $resources = $collector->getResources();
        var_dump($resources);
/*
        $analyzer = new \App\Misc\Analyzer($resources);
        $resultOfAnalysis = $analyzer->getAnalysis();
*/
        return 'OK';
    }


}
