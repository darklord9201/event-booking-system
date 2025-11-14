<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseControllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login;
use App\Http\Requests\Auth\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseApiController
{
    public function register(Register $registerRequest)
    {
        return $this->handleRequest(function () use ($registerRequest) {
            $data = $registerRequest->validated();
            $user = DB::transaction(function () use ($data) {
                return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone' => $data['phone'],
                    'role' => $data['role']
                ]);
            });

            return $this->respondSuccess($user, "User Registered Successfully");
        }, $registerRequest);
    }

    public function login(Login $loginRequest)
    {
        return $this->handleRequest(function () use ($loginRequest) {
            $data = $loginRequest->validated();
            $user = User::where('email', $data['email'])->first();
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return $this->respondError('Invalid credentials', 401);
            }


            $accessToken = $user->createToken('access_token');
            $data = [
                'access_token' => $accessToken->plainTextToken
            ];

            return $this->respondSuccess($data, 'Logged in Successfully.');
        }, $loginRequest);
    }

    public function logout(Request $request)
    {
        return $this->handleRequest(function () use ($request) {
            $request->user()->currentAccessToken()->delete();

            return $this->respondSuccess([], 'Logged Out Successfully.');
        }, $request);
    }
}
