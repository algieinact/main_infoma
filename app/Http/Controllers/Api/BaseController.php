<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * @param  mixed  $result
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * Error response method.
     *
     * @param  string  $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response method.
     *
     * @param  array  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendValidationError(array $errors): JsonResponse
    {
        return $this->sendError('Validation Error', $errors, 422);
    }

    /**
     * Unauthorized error response method.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendUnauthorizedError(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->sendError($message, [], 401);
    }

    /**
     * Forbidden error response method.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendForbiddenError(string $message = 'Forbidden'): JsonResponse
    {
        return $this->sendError($message, [], 403);
    }
} 