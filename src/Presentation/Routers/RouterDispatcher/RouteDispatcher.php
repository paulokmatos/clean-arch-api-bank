<?php

namespace App\Presentation\Routers\RouterDispatcher;

use Closure;

class RouteDispatcher
{
    /**
     *@throws \JsonException
     */
    public static function dispatch(Closure $callback): void
    {
        try {
            $request = self::getRequestBody();

            $response = $callback($request);

            if (!$response instanceof JsonResponse) {
                throw new \RuntimeException("Callback return must be an instance of " . JsonResponse::class, 500);
            }

            self::setStatusCode($response->status);

            echo json_encode($response->content, JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            self::setStatusCode($exception->getCode());
            echo json_encode(['message' => $exception->getMessage()], JSON_THROW_ON_ERROR);
        }
    }

    private static function setStatusCode(int $code): void
    {
        header('Content-Type: application/json', true, $code);
    }

    /**
     * @throws \RuntimeException|\JsonException
     */
    private static function getRequestBody(): Request
    {
        $jsonContent = file_get_contents('php://input');
        $data = $_GET;

        if(!empty($jsonContent) && !json_validate($jsonContent)) {
            throw new \RuntimeException("Request body is not valid", 400);
        }

        if (!empty($jsonContent)) {
            $data = [...$data, json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR)];
        }

        return new Request($data);
    }
}
