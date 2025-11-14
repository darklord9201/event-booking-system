<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseControllers\ProtectedApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends ProtectedApiController
{
    public function index(Request $request)
    {
        return $this->handleRequest(function () {
            $currentUser = auth('sanctum')->user()->only('name', 'email');

            return $this->respondSuccess($currentUser, 'Profile Fetched Successfully.');
        }, $request, 'Profile fetched successfully');
    }
}
