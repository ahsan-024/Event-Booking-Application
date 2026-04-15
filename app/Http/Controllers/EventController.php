<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('date')) {
            $query->whereDate('event_datetime', $request->input('date'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        return response()->json($query->paginate(15));
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        $description = isset($validated['description']) ? $validated['description'] : null;

        $event = Event::create([
            'title'           => $validated['title'],
            'description'     => $description,
            'location'        => $validated['location'],
            'event_datetime'  => $validated['event_datetime'],
            'total_seats'     => $validated['total_seats'],
            'available_seats' => $validated['total_seats'],
            'created_by'      => auth()->id(),
        ]);

        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        return response()->json($event);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->noContent();
    }
}
