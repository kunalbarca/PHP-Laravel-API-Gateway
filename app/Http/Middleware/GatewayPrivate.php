<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper as Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Client; 
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;

class GatewayPrivate
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

        //Get service config
        $service_name = Helper::service($path);
        if($service_name == ''){
            return response()->json(array('message'=>'Invalid Endpoint'), 404);
        }else{
            $service_config = Config::get('gateway.services');
            $private_endpoint = $service_config[$service_name];
            $redirect_url = $request_protocol . $private_endpoint . '/' . $path;
            
            //If private, check token
            $token = $request->header('X-Jwt-Token');

            if($token == ''){
                return response()->json(array('message'=>'token_absent'), 400);
            }else {
                $userId = Helper::token($token);
                print_r($userId);
                exit();
                return $response = $http->request($request_method,$redirect_url, [
                    'headers' => [
                        'userId' => $userId,
                    ],
                ]);
            }
        }
        
        return $next($request);
    }
}
