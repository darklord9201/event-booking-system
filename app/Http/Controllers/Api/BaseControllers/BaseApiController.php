<?php

namespace App\Http\Controllers\Api\BaseControllers;

use App\Concerns\HandlesApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    use HandlesApiResponse;

    protected $request;
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = auth('sanctum')->user();
        $this->request = $request;
    }

    /**
     * @param callable $callable
     * @param Request|null $request
     * @param string $message
     * @return JsonResponse
     */
    public function handleRequest(callable $callable, Request $request = null): JsonResponse
    {
        try {
            if ($request) {
                $this->request = $request;
            }

            $response = $callable() ?? [];

            if (isset($response)) {
                return $response;
            }

        } catch (\Throwable $exception) {
            // Handle and log exceptions
            return $this->handleGlobalException($exception, $this->request);
        }
    }
}
