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
        var_dump($downloader); die;
        $url = $request->input('url');
        $downloader->download($url);
        if ($downloader->error !== null) {
            return view('error', ['error' => $downloader->error]);
        }

        $downloadedHtmlPage = \App\Misc\Helper::clearUnusedSymbols($downloader->response->getBody());
        $html = new Html($downloadedHtmlPage, $downloader->method, $downloader->domain);
        $html->setStats($downloader->stats);

        $collector = app(\App\Misc\Collector::class);
        $collector->setHtml($html);
        $collector->getResources();

        $analyzer = new \App\Misc\Analyzer($collector);
        $resultOfAnalyzes = $analyzer->getAnalyzes();

     //   var_dump($resultOfAnalyzes);
        return view('analyzer', $resultOfAnalyzes);
    }


}
