<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse(mixed $result = '', string $message = '', int $status = 200)
    {
        $response = [
            'success' => true,
        ];

        if (! empty($message)) {
            $response['message'] = $message;
        }

        if (! empty($result)) {
            $response['data'] = $result;
        }

        $executionTime = microtime(true) - LARAVEL_START;
        $memoryUsage = memory_get_usage(true);

        return response()
            ->json($response, $status)
            ->header('X-Debug-Time', $executionTime * 1000)
            ->header('X-Debug-Memory', round($memoryUsage / 1024, 2));;
    }

    public function sendError($error, $errorMessages = [], $status = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        $executionTime = microtime(true) - LARAVEL_START;
        $memoryUsage = memory_get_usage(true);

        return response()
            ->json($response, $status)
            ->header('X-Debug-Time', $executionTime * 1000)
            ->header('X-Debug-Memory', round($memoryUsage / 1024, 2));
    }
}
