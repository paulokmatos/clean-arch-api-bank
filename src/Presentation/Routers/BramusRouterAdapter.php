<?php

namespace App\Presentation\Routers;

use App\Presentation\Routers\RouterDispatcher\RouteDispatcher;
use Bramus\Router\Router as BramusRouter;
use Closure;

class BramusRouterAdapter implements IRouterAdapter
{
    public const array VALID_METHODS = [
        'post',
        'get',
        'put',
        'patch',
        'delete'
    ];

    private BramusRouter $router;

    public function __construct()
    {
        $this->router = new BramusRouter();
    }

    public function register(string $method, string $uri, Closure $callback): void
    {
        if (!in_array(strtolower($method), self::VALID_METHODS)) {
            throw new \RuntimeException("Invalid method {$method}", 500);
        }

        $this->router->match(strtoupper($method), $uri, function () use ($callback) {
            RouteDispatcher::dispatch($callback);
        });

        $this->router->run();
    }
}
