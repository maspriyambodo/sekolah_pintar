<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\CursorPaginator;

trait ApiResponseTrait
{
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $code = 200,
        ?array $meta = null
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    protected function errorResponse(
        string $message = 'Error',
        int $code = 400,
        ?array $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function paginatedResponse(
        $paginator,
        string $message = 'Success',
        ?string $resourceClass = null
    ): JsonResponse {
        $data = $resourceClass
            ? $resourceClass::collection($paginator->items())
            : $paginator->items();

        // Handle both CursorPaginator and LengthAwarePaginator
        $meta = [];

        if (method_exists($paginator, 'currentPage')) {
            $meta['current_page'] = $paginator->currentPage();
        }

        if (method_exists($paginator, 'nextCursor')) {
            $meta['next_cursor'] = $paginator->nextCursor()?->encode();
            $meta['prev_cursor'] = $paginator->previousCursor()?->encode();
            $meta['has_more'] = $paginator->hasMorePages();
        }

        if (method_exists($paginator, 'lastPage')) {
            $meta['last_page'] = $paginator->lastPage();
            $meta['total'] = $paginator->total();
        }

        return $this->successResponse($data, $message, 200, $meta);
    }

    protected function noContentResponse(string $message = 'No content'): JsonResponse
    {
        return $this->successResponse(null, $message, 204);
    }

    protected function createdResponse(
        mixed $data = null,
        string $message = 'Created successfully'
    ): JsonResponse {
        return $this->successResponse($data, $message, 201);
    }

    protected function validationErrorResponse(array $errors): JsonResponse
    {
        return $this->errorResponse('Validation failed', 422, $errors);
    }

    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
}
