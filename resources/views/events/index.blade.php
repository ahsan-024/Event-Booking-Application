@extends('layouts.app')
@section('title', 'Browse Events — TicketFlow')
@section('content')

{{-- Hero --}}
<div style="background:#355872;padding:4rem 0 5rem;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full mb-5" style="background:rgba(156,213,255,.15);color:#9CD5FF;">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#9CD5FF;"></span>
            Live events available now
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 leading-tight">
            Discover Amazing Events<br>
            <span style="color:#9CD5FF;">Near You</span>
        </h1>
        <p class="text-lg mb-10 max-w-xl mx-auto" style="color:#c8dff0;">
            From concerts to conferences — find and book your next unforgettable experience.
        </p>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('web.events.index') }}"
            class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-2 flex flex-col sm:flex-row gap-2">
            <div class="flex-1 flex items-center gap-2 px-3">
                <svg class="w-5 h-5 flex-shrink-0" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <input type="text" name="location" value="{{ request('location') }}" placeholder="Search by location..."
                    class="flex-1 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none bg-transparent">
            </div>
            <div class="flex items-center gap-2 px-3" style="border-left:1px solid #e8f0f7;">
                <svg class="w-5 h-5 flex-shrink-0" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="py-2.5 text-sm text-gray-800 focus:outline-none bg-transparent">
            </div>
            <button type="submit"
                style="background:#355872;color:#fff;font-weight:600;border-radius:.875rem;padding:.75rem 1.5rem;font-size:.875rem;border:none;cursor:pointer;white-space:nowrap;transition:background .15s;"
                onmouseover="this.style.background='#2a4660'"
                onmouseout="this.style.background='#355872'">
                Search Events
            </button>
        </form>
    </div>
</div>

{{-- Stats bar --}}
<div class="bg-white" style="border-bottom:1px solid #dde8f0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3 text-sm" style="color:#6b8fa8;">
            <span class="font-bold" style="color:#355872;">{{ $events->total() }}</span> events found
            @if(request('location') || request('date'))
                <span class="flex items-center gap-1.5 text-xs font-medium px-3 py-1 rounded-full" style="background:#e0f4ff;color:#355872;">
                    Filtered results
                    <a href="{{ route('web.events.index') }}" style="color:#355872;">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </span>
            @endif
        </div>
        @auth
            <a href="{{ route('web.events.create') }}"
                style="display:inline-flex;align-items:center;gap:.375rem;background:#355872;color:#fff;font-size:.8125rem;font-weight:600;padding:.5rem 1rem;border-radius:.625rem;text-decoration:none;transition:background .15s;"
                onmouseover="this.style.background='#2a4660'"
                onmouseout="this.style.background='#355872'">
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
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#e0f4ff;">
                <svg class="w-10 h-10" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold mb-2" style="color:#355872;">No events found</h3>
            <p class="text-sm mb-6" style="color:#6b8fa8;">Try adjusting your search filters</p>
            <a href="{{ route('web.events.index') }}" class="text-sm font-medium hover:underline" style="color:#355872;">Clear filters</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $banners = ['#355872','#7AAACE','#4a7a9b','#2a5f7a','#5a8fa8','#3d6e8a'];
                    $bg = $banners[$event->id % count($banners)];
                    $month = \Carbon\Carbon::parse($event->event_datetime)->format('M');
                    $day   = \Carbon\Carbon::parse($event->event_datetime)->format('d');
                @endphp
                <a href="{{ route('web.events.show', $event->id) }}"
                    class="group bg-white rounded-2xl overflow-hidden flex flex-col transition-all duration-200 hover:-translate-y-1"
                    style="border:1px solid #dde8f0;box-shadow:0 1px 4px rgba(53,88,114,.07);text-decoration:none;">
                    {{-- Banner --}}
                    <div class="h-36 relative flex items-end p-4" style="background:{{ $bg }};">
                        <div class="absolute top-4 right-4 text-white text-xs font-semibold px-2.5 py-1 rounded-full" style="background:rgba(255,255,255,.2);">
                            {{ $event->available_seats > 0 ? $event->available_seats . ' seats left' : 'Sold Out' }}
                        </div>
                        <div class="bg-white rounded-xl w-12 text-center py-1.5 shadow-lg">
                            <div class="text-xs font-bold uppercase leading-none" style="color:#7AAACE;">{{ $month }}</div>
                            <div class="text-xl font-extrabold leading-tight" style="color:#355872;">{{ $day }}</div>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <h2 class="text-base font-bold mb-2 line-clamp-2 leading-snug transition-colors" style="color:#1a2e3d;">
                            {{ $event->title }}
                        </h2>
                        <div class="space-y-1.5 mb-3">
                            <div class="flex items-center gap-2 text-xs" style="color:#6b8fa8;">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs" style="color:#6b8fa8;">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ \Carbon\Carbon::parse($event->event_datetime)->format('D, M d · H:i') }}
                            </div>
                        </div>
                        @if($event->description)
                            <p class="text-xs line-clamp-2 mb-3 leading-relaxed" style="color:#8aacbf;">{{ $event->description }}</p>
                        @endif
                        <div class="mt-auto flex items-center justify-between pt-3" style="border-top:1px solid #f0f7ff;">
                            @if($event->available_seats > 0)
                                <span style="display:inline-flex;align-items:center;gap:.25rem;background:#e0f4ff;color:#2a6a9a;font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px;">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:#7AAACE;"></span>
                                    Available
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;background:#fde8e8;color:#b91c1c;font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px;">Sold Out</span>
                            @endif
                            <span class="text-xs font-semibold flex items-center gap-1 transition-colors" style="color:#355872;">
                                View details
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
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
