<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Send a JSON response and optional Log a "view" activity.
     *
     * @param $status
     * @param $message
     * @param null $data
     * @param null $class
     * @return JsonResponse
     */
    public function response($status, $message, $data = null, $class = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Sends a JSON error response.
     *
     * @param $status
     * @param $message
     * @param null $errors
     * @return JsonResponse
     */
    public function error($status, $message, $errors = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Handles an authorization error.
     *
     * @param null $message
     * @return JsonResponse
     */
    public function authorizationError($message = null): JsonResponse
    {
        return $this->error(Response::HTTP_FORBIDDEN, 'Authorization Failed', $message);
    }

    /**
     * Handles an authentication error.
     *
     * @param null $message
     * @return JsonResponse
     */
    public function authenticationError($message = null): JsonResponse
    {
        return $this->error(Response::HTTP_UNAUTHORIZED, 'Authentication Failed', $message);
    }

    /**
     * Handles a validation error.
     *
     * @param $errors
     * @return JsonResponse
     */
    public function validationError($errors): JsonResponse
    {
        return $this->error(Response::HTTP_UNPROCESSABLE_ENTITY, 'Validation error', $errors);
    }

    /**
     * Handles a server error.
     *
     * @return JsonResponse
     */
    public function serverError(): JsonResponse
    {
        return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal server error occurred');
    }
}
