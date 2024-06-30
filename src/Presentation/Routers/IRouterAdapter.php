<?php

namespace App\Presentation\Routers;

interface IRouterAdapter
{
    public function register(string $method, string $uri, array $params): void;
}
