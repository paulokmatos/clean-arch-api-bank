<?php

namespace App\Presentation\Routers\RouterDispatcher;

readonly class JsonResponse
{
    public function __construct(public array $content = [], public int $status = 200)
    {
        //
    }
}
