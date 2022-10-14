<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    /**
     * Return the corresponding json response with the corresponding status.
     *
     * @param  array  $request
     * @param  int|null  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse(array $response, int $statusCode = 200): JsonResponse
    {
        return response()->json(
            $response, $statusCode
        );
    }

    /**
     * Return the corresponding json response error with the corresponding status.
     *
     * @param  array  $request
     * @param  int|null  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponseError(array $response, int $statusCode = 404): JsonResponse
    {
        return response()->json(
            $response, $statusCode
        );
    }
}
