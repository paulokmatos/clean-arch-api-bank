<?php

namespace App\Presentation\Routers;

use App\Presentation\Controllers\Controller;
use App\Presentation\Routers\RouterDispatcher\RouteDispatcher;
use Bramus\Router\Router as BramusRouter;

class RouterAdapter implements IRouterAdapter
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

    /**
     * @throws \Exception
     */
    public function register(string $method, string $uri, array $params): void
    {
        $this->validateRoute($method, $params);

        $this->router->match(strtoupper($method), $uri, function () use ($params) {
            RouteDispatcher::dispatch($params);
        });

        $this->router->run();
    }

    private function validateRoute(string $method, array $params): void
    {
        if (!in_array(strtolower($method), self::VALID_METHODS)) {
            throw new \RuntimeException("Invalid method {$method}", 500);
        }

        if (count($params) !== 2) {
            throw new \RuntimeException("params must receive 2 arguments, class and method", 500);
        }

        if (!class_exists($params[0]) || !in_array(Controller::class, class_implements($params[0]), true)) {
            throw new \RuntimeException($params[0] . " must be instance of " . Controller::class, 500);
        }

        if (!method_exists($params[0], $params[1])) {
            throw new \RuntimeException("method $params[1] not found in $params[0]", 500);
        }
    }
}
