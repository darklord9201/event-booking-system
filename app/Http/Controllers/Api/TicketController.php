<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseControllers\ProtectedApiController;
use App\Http\Requests\Ticket\Store;
use App\Http\Requests\Ticket\Update;
use App\Models\Event;
use App\Models\ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends ProtectedApiController
{
    public function index()
    {

    }

    public function store(Event $event, Store $storeRequest)
    {
        return $this->handleRequest(function () use ($storeRequest, $event) {
            $data = $storeRequest->validated();
            $ticket = DB::transaction(function () use ($event, $data) {
                return $event->tickets()->create($data);
            });

            return $this->respondSuccess($ticket, 'Ticket Created Successfully.');
        }, $storeRequest);
    }

    public function update(Ticket $ticket, Update $updateRequest)
    {
        return $this->handleRequest(function () use ($updateRequest, $ticket) {
            $data = $updateRequest->validated();
            DB::transaction(function () use ($ticket, $data) {
                return $ticket->update($data);
            });

            return $this->respondSuccess($ticket, 'Ticket Updated Successfully.');
        }, $updateRequest);
    }


    public function destroy(Ticket $ticket, Request $request)
    {
        return $this->handleRequest(function () use ($ticket) {
            DB::transaction(function () use ($ticket) {
                return $ticket->delete();
            });

            return $this->respondSuccess($ticket, 'Ticket Deleted Successfully.');
        }, $request);
    }
}
