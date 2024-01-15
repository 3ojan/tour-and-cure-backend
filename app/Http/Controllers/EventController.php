<?php

namespace App\Http\Controllers;

use App\Http\Requests\Events\EventViewAllRequest as ViewAll;
use App\Http\Requests\Events\EventStoreRequest as Store;
use App\Http\Requests\Events\EventViewRequest as View;
use App\Http\Requests\Events\EventUpdateRequest as Update;
use App\Http\Requests\Events\EventDeleteRequest as Delete;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(ViewAll $request)
    {
        $perPage = $request->input('per_page', 20);
        $user = auth()->user();

        $events = Event::when($user->isClinicOwner() || $user->isClinicUser(), function ($query) use ($user) {
            return $query->whereHas('user', function ($subquery) use ($user) {
                $subquery->where('clinic_id', $user->clinic_id);
            });
        })
            ->paginate($perPage);

        return response()->json(array_merge([
            'status' => 'Success',
            'message' => 'All events fetched successfully!',
        ], EventResource::collection($events)->toArray()), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request)
    {
        $event = Event::create([
            'clinic_id' => Auth::user()->clinic_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'title' => $request->title,
            'location' => $request->location,
            'data' => $request->data
        ]);

        $event->employees()->attach($request->employee_ids);

        return $this->success(new EventResource($event), 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(View $request, Event $event)
    {
        return $this->success(new EventResource($event), 'Event type fetched successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Event $event)
    {
        $event->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'title' => $request->title,
            'location' => $request->location,
            'data' => $request->data
        ]);

        $event->employees()->sync($request->employee_ids);

        return $this->success(new EventResource($event), 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delete $request, Event $event)
    {
        $event->delete();

        return $this->success('', 'Event deleted successfully!');
    }
}
