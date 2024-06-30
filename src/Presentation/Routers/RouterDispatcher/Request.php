<?php

namespace App\Presentation\Routers\RouterDispatcher;

readonly class Request
{
    public function __construct(private array $content)
    {
        //
    }

    public function get(string $key): array|bool|float|int|string|null
    {
        return $this->content[$key] ?? null;
    }
}
