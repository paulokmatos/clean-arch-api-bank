<?php

namespace Tests\Presentation\Controllers;

use App\Presentation\Controllers\HealthCheckController;
use PHPUnit\Framework\TestCase;

class HealthCheckControllerTest extends TestCase
{
    public function test_ShouldReturnSuccess(): void
    {
        $controller = new HealthCheckController();

        $response = $controller->status();

        $this->assertTrue($response->content['success']);
    }
}