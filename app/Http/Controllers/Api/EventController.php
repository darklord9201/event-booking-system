<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseControllers\ProtectedApiController;
use App\Http\Requests\Event\Store;
use App\Http\Requests\Event\Update;
use App\Http\Resources\EventCollecion;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends ProtectedApiController
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = auth('sanctum')->user();

        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return $this->handleRequest(function () {
            $events = $this->user->events()->paginate(10);

            return $this->respondWithPagination(
                $events,
                \App\Http\Resources\Event::class,
                'Events fetched successfully'
            );

        }, $request);
    }

    public function store(Store $storeRequest)
    {
        return $this->handleRequest(function () use ($storeRequest) {
            $data = $storeRequest->validated();
            $event =  DB::transaction(function () use ($data){
                return $this->user->events()->create($data);
            });

            return $this->respondSuccess(new \App\Http\Resources\Event($event), 'Event Created Successfully.');

        } , $storeRequest);
    }

    public function update(Event $event, Update $updateRequest)
    {
        return $this->handleRequest(function () use ($updateRequest, $event) {

            $data = $updateRequest->validated();
            DB::transaction(function () use ($event, $data){
                $event->update($data);
            });

            return $this->respondSuccess(new \App\Http\Resources\Event($event), 'Event Updated Successfully.');
        }, $updateRequest);

    }

    public function destroy(Event $event, Request $request)
    {
        return $this->handleRequest(function () use ($event) {
            $eventTitle = $event->title;
            DB::transaction(function () use ($event){
                $event->delete();
            });

            return $this->respondSuccess($event, 'Event - "' . $eventTitle . '" Deleted Successfully.');
        }, $request);
    }
}
