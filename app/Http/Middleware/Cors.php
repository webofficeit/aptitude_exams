<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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

        header("Access-Control-Allow-Origin: http://localhost:3000");

        // ALLOW OPTIONS METHOD
        $headers = [
            'Access-Control-Allow-Headers'=> 'Content-Type, Accept, Authorization, X-Requested-With, Application, Method',
            'Access-Control-Allow-Credentials'=> 'true'
        ];


         // The client-side application can set only headers allowed in Access-Control-Allow-Headers
//        if($request->getMethod() == "OPTIONS") {
//            return Response::make('OK', 200, $headers);
//        }

        $response = $next($request);
        foreach($headers as $key => $value)
            $response->header($key, $value);
        return $response;
    }


//    public function handle($request, Closure $next)
//    {
//        return $next($request)
//            ->header('Access-Control-Allow-Origin', 'http://localhost:3000/')
//            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//    }
}
