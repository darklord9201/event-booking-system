<?php

namespace App\Concerns;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Trait HandlesApiResponse
 * Provides standardized JSON response methods for API controllers, including
 * success, error, pagination, and internal error handling. Use this trait
 * in controllers to ensure consistent API responses.
 */
trait HandlesApiResponse
{
    /**
     * Standard API success response
     *
     * @param mixed $data Response payload
     * @param string $message Descriptive message
     * @param int $status HTTP status code
     */
    public function respondSuccess($data = null, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => null,
        ], $status);
    }

    /**
     * Format paginated API responses with meta information.
     *
     * @param string|null $resourceClass API Resource class for transformation
     * @param string $message Optional message
     */
    public function respondWithPagination(AbstractPaginator $paginatedData, ?string $resourceClass = null, string $message = ''): JsonResponse
    {
        $responseData = [
            'items' => $resourceClass ? $resourceClass::collection($paginatedData->getCollection()) : $paginatedData->items(),

            'meta' => [
                'current_page' => $paginatedData->currentPage(),
                'last_page'    => $paginatedData->lastPage(),
                'per_page'     => $paginatedData->perPage(),
                'total'        => $paginatedData->total(),
            ],
        ];

        return $this->respondSuccess($responseData, $message);
    }

    /**
     * Standard API Error response
     *
     * @param string $message Error message
     * @param int $code HTTP status code
     * @param array|null $errors Optional error details (hidden in production)
     */
    public function respondError(string $message, int $code = 422, ?array $errors = null): JsonResponse
    {
        return self::respondFormattedError($message, $code, $errors);

    }

    public static function respondFormattedError(string $message, int $code = 422, ?array $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors ?? [],
        ], $code);
    }

    /**
     * Global Exception Handler
     *
     * @param Throwable $exception Error message
     * @param Request $request HTTP status code
     */
    public function handleGlobalException(Throwable $exception, Request $request)
    {
        //Validation Error
        if ($exception instanceof ValidationException) {
            return self::respondError("Validation failed", 422, $exception->errors());
        }
        //Authorization Error
        if ($exception instanceof AuthorizationException) {
            return self::respondError("Unauthorized", 403, [ 'authorization' => $exception->getMessage() ]);
        }

        // Authentication Error
        if ($exception instanceof AuthenticationException) {
            return self::respondError("Authentication required", 403, [ 'Authentication' => $exception->getMessage() ]);
        }

        // Generic HTTP exceptions
        if ($exception instanceof HttpException) {
            return self::respondError($exception->getMessage(), $exception->getStatusCode());
        }

        return self::respondError($exception->getMessage(), 500);
    }

}
