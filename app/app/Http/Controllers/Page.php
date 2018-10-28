<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Misc\Helper;

class Page extends BaseController
{
    public function handle(Request $request, Client $client)
    {
        $url = $request->input('url');
        try {
            $response = $client->get($url);
        } catch (\Exception $e) {
            return 'Resource not found';
        }
        $code = $response->getStatusCode();

        if ($code !== 200) {
            return 'Not available resource';
        }
        $headers = $response->getHeader('Content-Type')[0] ?? '';

        if (!Helper::isHtml($headers)) {
            return 'Not HTML resource';
        }
        return 'OK';
    }

    private function isHtml($str)
    {
        
    }
}
