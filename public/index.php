<?php

use App\Presentation\Routers\Router;
use App\Presentation\Routers\RouterAdapter;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router(new RouterAdapter());

$router->register();