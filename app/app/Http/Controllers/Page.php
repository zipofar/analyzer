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

        $downloadedHtmlPage = \App\Misc\Helper::clearUnusedSymbols($resource->response->getBody());
        $html = new Html($downloadedHtmlPage, $resource->method, $resource->domain);
        $html->setStats($resource->stats);

        $collector = app(\App\Misc\Collector::class);
        $collector->setHtml($html);
        $collector->getResources();

        $analyzer = new \App\Misc\Analyzer($collector);
        $resultOfAnalyzes = $analyzer->getAnalyzes();
        var_dump($resultOfAnalyzes);

        return 'OK';
    }


}
