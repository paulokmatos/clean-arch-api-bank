<?php

namespace App\Presentation\Controllers;

use App\Presentation\Routers\RouterDispatcher\JsonResponse;

class HealthCheckController implements Controller
{
    public function status(): JsonResponse
    {
        return new JsonResponse(content: ['success' => true]);
    }
}
