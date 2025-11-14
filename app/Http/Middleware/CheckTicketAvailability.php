<?php

namespace App\Http\Middleware;

use App\Models\Ticket;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTicketAvailability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ticket =  $request->route('ticket');
        $bookedTicketQuantity = $ticket->bookings()->where('status', '!=', 'CANCELLED')->sum('quantity');
        $availableQuantity = $ticket->quantity - $bookedTicketQuantity;
        $requestedQuantity = $request->get('quantity');

        if ($requestedQuantity > $availableQuantity){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Booking Request',
                'data' => [],
                'error' => 'Available Quantity : ' . $availableQuantity . " | " . 'Requested Quantity: ' . $requestedQuantity
            ]);
        }

        return $next($request);
    }
}
