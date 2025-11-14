<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseControllers\ProtectedApiController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends ProtectedApiController
{
    public function show()
    {

    }


    public function store(Request $request, Booking $booking)
    {
        return $this->handleRequest(function() {
        }, $request);
    }
}
