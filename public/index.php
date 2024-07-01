<?php

use App\Presentation\Controllers\HealthCheckController;
use App\Presentation\Routers\BramusRouterAdapter;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new BramusRouterAdapter();

$router->register('get', '/health-check', (new HealthCheckController())->status(...));

$router->run();