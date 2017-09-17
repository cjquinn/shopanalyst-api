<?php

namespace App\Routing\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CorsMiddleware
{

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if ($request->getHeader('Origin')) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Access-Control-Max-Age', '86400');

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                $response = $response
                    ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type, X-Requested-With')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');

                return $response;
            }
        }

        return $next($request, $response);
    }
}
