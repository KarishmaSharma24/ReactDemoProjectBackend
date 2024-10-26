<?php

namespace App\Traits;

trait CustomResponseTrait
{
    public function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'statusCode' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function errorResponse($message = null, $code = 400)
    {
        return response()->json([
            'statusCode' => $code,
            'message' => $message,
        ], $code);
    }
}