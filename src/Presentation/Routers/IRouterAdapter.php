<?php

namespace App\Presentation\Routers;

use Closure;

interface IRouterAdapter
{
    public function register(string $method, string $uri, Closure $callback): void;

    public function run(): void;
}
