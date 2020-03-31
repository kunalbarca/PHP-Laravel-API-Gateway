<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper as Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Client; 
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;

class Gateway
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Guzzle
        $http = new \GuzzleHttp\Client;

        //Request method
        $request_method = $request->method();

        //Request protocol
        $request_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';

        //your api_url
        $url = $request->url();
        
        //Check for Endpoint
        $path = $request->path();
        
        //check if public endpoint
        $public_endpoint = Helper::publicEndpoints($path);

        if($public_endpoint){
            $redirect_url = $request_protocol . $public_endpoint . '/' . $path;
            return $response = $http->request($request_method, $redirect_url);
        }
        return $next($request);
    }
}
