<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Middleware\CheckTicketAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/me', [ProfileController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'events'], function () {
        Route::get('', [EventController::class, 'index']);
        Route::post('', [EventController::class, 'store']);
        Route::put('{event}', [EventController::class, 'update']);
        Route::delete('{event}', [EventController::class, 'destroy']);
        Route::post('{event}/tickets', [TicketController::class, 'store']);
    });

    Route::group(['prefix' => 'tickets'], function () {
        Route::put('{ticket}', [TicketController::class, 'update']);
        Route::delete('{ticket}', [TicketController::class, 'destroy']);
        Route::post('{ticket}/bookings', [BookingController::class, 'store'])->middleware(CheckTicketAvailability::class);
    });

    Route::group(['prefix' => 'bookings'], function () {
        Route::get('', [BookingController::class, 'index']);
        Route::put('{booking}/cancel', [BookingController::class, 'cancelBooking']);
    });


});

