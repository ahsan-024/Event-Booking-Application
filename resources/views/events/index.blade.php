@extends('layouts.app')
@section('title', 'Browse Events — TicketFlow')
@section('content')

{{-- Hero --}}
<div style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 40%,#4c1d95 70%,#6d28d9 100%);padding:4rem 0 5rem;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 bg-white/10 text-purple-200 text-xs font-semibold px-3 py-1.5 rounded-full mb-5 backdrop-blur">
            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
            Live events available now
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 leading-tight">
            Discover Amazing Events<br>
            <span style="background:linear-gradient(90deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Near You</span>
        </h1>
        <p class="text-purple-200 text-lg mb-10 max-w-xl mx-auto">
            From concerts to conferences — find and book your next unforgettable experience.
        </p>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('web.events.index') }}"
            class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-2 flex flex-col sm:flex-row gap-2">
            <div class="flex-1 flex items-center gap-2 px-3">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <input type="text" name="location" value="{{ request('location') }}" placeholder="Search by location..."
                    class="flex-1 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none bg-transparent">
            </div>
            <div class="flex items-center gap-2 px-3 border-l border-gray-100">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="py-2.5 text-sm text-gray-800 focus:outline-none bg-transparent">
            </div>
            <button type="submit"
                style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;font-weight:600;border-radius:0.875rem;padding:0.75rem 1.5rem;font-size:0.875rem;border:none;cursor:pointer;white-space:nowrap;">
                Search Events
            </button>
        </form>
    </div>
</div>

{{-- Stats bar --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-6 text-sm text-gray-500">
            <span class="font-semibold text-gray-900">{{ $events->total() }}</span> events found
            @if(request('location') || request('date'))
                <span class="flex items-center gap-1.5 bg-violet-50 text-violet-700 text-xs font-medium px-3 py-1 rounded-full">
                    Filtered results
                    <a href="{{ route('web.events.index') }}" class="hover:text-violet-900">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </span>
            @endif
        </div>
        @auth
            <a href="{{ route('web.events.create') }}"
                style="display:inline-flex;align-items:center;gap:0.375rem;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;font-size:0.8125rem;font-weight:600;padding:0.5rem 1rem;border-radius:0.625rem;text-decoration:none;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Create Event
            </a>
        @endauth
    </div>
</div>

{{-- Event grid --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($events->isEmpty())
        <div class="text-center py-24">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No events found</h3>
            <p class="text-gray-400 text-sm mb-6">Try adjusting your search filters</p>
            <a href="{{ route('web.events.index') }}" class="text-violet-600 font-medium hover:underline text-sm">Clear filters</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $colors = ['from-violet-500 to-purple-600','from-pink-500 to-rose-500','from-blue-500 to-indigo-600','from-emerald-500 to-teal-600','from-orange-500 to-amber-500','from-cyan-500 to-blue-500'];
                    $color = $colors[$event->id % count($colors)];
                    $month = \Carbon\Carbon::parse($event->event_datetime)->format('M');
                    $day   = \Carbon\Carbon::parse($event->event_datetime)->format('d');
                @endphp
                <a href="{{ route('web.events.show', $event->id) }}"
                    class="group bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-violet-200 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl flex flex-col"
                    style="text-decoration:none;">
                    {{-- Color banner with date badge --}}
                    <div class="bg-gradient-to-r {{ $color }} h-36 relative flex items-end p-4">
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                            {{ $event->available_seats > 0 ? $event->available_seats . ' seats left' : 'Sold Out' }}
                        </div>
                        <div class="bg-white rounded-xl w-12 text-center py-1.5 shadow-lg">
                            <div class="text-xs font-bold text-gray-500 uppercase leading-none">{{ $month }}</div>
                            <div class="text-xl font-extrabold text-gray-900 leading-tight">{{ $day }}</div>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <h2 class="text-base font-bold text-gray-900 mb-2 group-hover:text-violet-700 transition-colors line-clamp-2 leading-snug">
                            {{ $event->title }}
                        </h2>
                        <div class="space-y-1.5 mb-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ \Carbon\Carbon::parse($event->event_datetime)->format('D, M d · H:i') }}
                            </div>
                        </div>
                        @if($event->description)
                            <p class="text-xs text-gray-400 line-clamp-2 mb-3 leading-relaxed">{{ $event->description }}</p>
                        @endif
                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-50">
                            @if($event->available_seats > 0)
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Available
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">Sold Out</span>
                            @endif
                            <span class="text-xs font-semibold text-violet-600 group-hover:text-violet-700 flex items-center gap-1">
                                View details
                                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $events->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
