<?php

namespace App\Test\TestCase;

use App\Application;
use App\Routing\Middleware\CorsMiddleware;

use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\IntegrationTestCase;

class ApplicationTest extends IntegrationTestCase
{

    /**
     * @return void
     */
    public function testMiddleware()
    {
        $app = new Application(dirname(dirname(__DIR__)) . '/config');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        $this->assertInstanceOf(CorsMiddleware::class, $middleware->get(0));
        $this->assertInstanceOf(ErrorHandlerMiddleware::class, $middleware->get(1));
        $this->assertInstanceOf(AssetMiddleware::class, $middleware->get(2));
        $this->assertInstanceOf(RoutingMiddleware::class, $middleware->get(3));
    }
}
