<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class WebEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()->orderBy('event_datetime');

        if ($request->filled('date')) {
            $query->whereDate('event_datetime', $request->input('date'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        $events = $query->paginate(12)->withQueryString();

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'required|string|max:255',
            'event_datetime' => 'required|date|after:now',
            'total_seats'    => 'required|integer|min:1',
        ]);

        Event::create([
            'title'           => $validated['title'],
            'description'     => $validated['description'] ?? null,
            'location'        => $validated['location'],
            'event_datetime'  => $validated['event_datetime'],
            'total_seats'     => $validated['total_seats'],
            'available_seats' => $validated['total_seats'],
            'created_by'      => auth()->id(),
        ]);

        return redirect()->route('web.events.index')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'          => 'sometimes|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'sometimes|string|max:255',
            'event_datetime' => 'sometimes|date|after:now',
            'total_seats'    => 'sometimes|integer|min:1',
        ]);

        $event->update($validated);

        return redirect()->route('web.events.show', $event->id)->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('web.events.index')->with('success', 'Event deleted.');
    }
}
