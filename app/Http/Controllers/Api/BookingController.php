<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseControllers\ProtectedApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\Store;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Ticket;
use App\Notifications\BookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends ProtectedApiController
{
    public function index(Request $request)
    {
        return $this->handleRequest(function () {
            $bookings = $this->user->bookings()->paginate(10);
            return $this->respondWithPagination($bookings, BookingResource::class, 'Bookings fetched successfully.');
        }, $request);
    }

    public function store(Store $storeRequest, Ticket $ticket)
    {
        return $this->handleRequest(function () use ($storeRequest, $ticket) {
            $data = $storeRequest->validated();
            $booking = DB::transaction(function () use ($data, $ticket) {
                return $ticket->bookings()->create(['user_id' => $this->user->id, 'status' => 'CONFIRMED', ...$data]);
            });

            $this->user->notify(new BookingConfirmed($booking));

            return $this->respondSuccess($booking, "Booking Created Successfully");
        }, $storeRequest);
    }

    public function cancelBooking(Request $request, Booking $booking)
    {
        return $this->handleRequest(function () use ($booking) {

            DB::transaction(function () use ($booking) {
                return $booking->update(['status' => 'CANCELLED']);
            });

            return $this->respondSuccess($booking, "Booking Cancelled Successfully");
        }, $request);
    }


}
