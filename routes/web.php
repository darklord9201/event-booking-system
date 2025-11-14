<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $ticket = \App\Models\Ticket::find(2);
    dd($ticket->bookings()->where('status', '!=', 'CANCELLED')->sum('quantity'));
    return view('welcome');
});
