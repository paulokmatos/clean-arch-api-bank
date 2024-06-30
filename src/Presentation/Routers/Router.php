<?php

namespace App\Presentation\Routers;

use App\Presentation\Controllers\HealthCheckController;

readonly class Router
{
    public function __construct(private IRouterAdapter $router)
    {
        //
    }

    public function register(): void
    {
        $this->router->register('get', '/health-check', [HealthCheckController::class, 'status']);
    }
}
