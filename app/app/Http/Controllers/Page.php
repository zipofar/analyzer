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
            $page = $downloader->download($url);
        } catch (\Exception $e) {
            return 'Resource not found';
        }

        if ($page->code !== 200) {
            return 'Not available resource';
        }

        if (!\App\Misc\Helper::isHtml($page->contentType)) {
            return 'Not HTML resource';
        }

        $tag = $page->response->getBody();
        $html = new Html($tag);



        $collector = new \App\Misc\Collector($html);
        //$resources = $collector->getResources();
/*
        $analyzer = new \App\Misc\Analyzer($resources);
        $resultOfAnalysis = $analyzer->getAnalysis();
*/
        return 'OK';
    }


}
