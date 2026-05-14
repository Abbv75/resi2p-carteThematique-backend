<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Return a success JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function success($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    protected function error(string $message, $data = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    /**
     * Return a not found JSON response.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, null, 404);
    }

    /**
     * Return a validation error JSON response.
     *
     * @param string $message
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function validationError(string $message = 'Validation failed', $errors = null): JsonResponse
    {
        return $this->error($message, $errors, 422);
    }
}
