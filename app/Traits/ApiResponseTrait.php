<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($data, $message = 'Success', $code = 200)
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'error' => null,
            'timestamp' => now(),
        ];
    }

    public function errorResponse($error, $message = 'Error', $code = 400)
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => null,
            'error' => $error,
            'timestamp' => now(),
        ];
    }
}
