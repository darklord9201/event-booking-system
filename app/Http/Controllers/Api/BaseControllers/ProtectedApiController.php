<?php

namespace App\Http\Controllers\Api\BaseControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProtectedApiController extends BaseApiController
{
    public function __construct(Request $request)
    {
        parent::__construct($request); // Proper parent constructor call
    }

    public static function middleware(): array
    {
        return [
            'auth:sanctum', // Sanctum authentication middleware
        ];
    }
}
