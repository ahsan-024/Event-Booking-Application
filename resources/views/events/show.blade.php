@extends('layouts.app')
@section('title', $event->title . ' — TicketFlow')
@section('content')

@php
    $colors = ['from-violet-500 to-purple-600','from-pink-500 to-rose-500','from-blue-500 to-indigo-600','from-emerald-500 to-teal-600','from-orange-500 to-amber-500','from-cyan-500 to-blue-500'];
    $color = $colors[$event->id % count($colors)];
@endphp

{{-- Hero banner --}}
<div class="bg-gradient-to-r {{ $color }} relative overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">
        <a href="{{ route('web.events.index') }}"
            class="inline-flex items-center gap-1.5 text-white/80 hover:text-white text-sm font-medium mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Events
        </a>
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
                <div class="flex flex-wrap gap-2 mb-3">
                    @if($event->available_seats > 0)
                        <span class="bg-emerald-400/20 text-emerald-100 text-xs font-semibold px-3 py-1 rounded-full backdrop-blur border border-emerald-300/30">
                            ✓ Available
                        </span>
                    @else
                        <span class="bg-red-400/20 text-red-100 text-xs font-semibold px-3 py-1 rounded-full backdrop-blur">Sold Out</span>
                    @endif
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight max-w-2xl">{{ $event->title }}</h1>
            </div>
            @auth
                @if(auth()->id() === $event->created_by)
                    <div class="flex gap-2 flex-shrink-0">
                        <a href="{{ route('web.events.edit', $event->id) }}"
                            class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur text-white text-sm font-medium px-4 py-2 rounded-xl hover:bg-white/30 transition border border-white/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('web.events.destroy', $event->id) }}"
                            onsubmit="return confirm('Delete this event? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-red-500/80 backdrop-blur text-white text-sm font-medium px-4 py-2 rounded-xl hover:bg-red-600 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>

{{-- Content --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Details card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-5">Event Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-0.5">Date & Time</div>
                            <div class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($event->event_datetime)->format('l, F d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->event_datetime)->format('H:i') }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-0.5">Location</div>
                            <div class="text-sm font-semibold text-gray-800">{{ $event->location }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-0.5">Capacity</div>
                            <div class="text-sm font-semibold text-gray-800">{{ $event->total_seats }} total seats</div>
                            <div class="text-sm {{ $event->available_seats > 0 ? 'text-emerald-600' : 'text-red-500' }} font-medium">
                                {{ $event->available_seats }} available
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-0.5">Organizer</div>
                            <div class="text-sm font-semibold text-gray-800">{{ $event->creator->name ?? 'Unknown' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seat availability bar --}}
            @php $pct = $event->total_seats > 0 ? round(($event->available_seats / $event->total_seats) * 100) : 0; @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-700">Seat Availability</span>
                    <span class="text-sm font-bold {{ $pct > 20 ? 'text-emerald-600' : 'text-red-500' }}">{{ $pct }}% available</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all {{ $pct > 50 ? 'bg-emerald-500' : ($pct > 20 ? 'bg-amber-500' : 'bg-red-500') }}"
                        style="width: {{ $pct }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-2">
                    <span>{{ $event->total_seats - $event->available_seats }} booked</span>
                    <span>{{ $event->available_seats }} remaining</span>
                </div>
            </div>

            @if($event->description)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">About this Event</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $event->description }}</p>
                </div>
            @endif
        </div>

        {{-- Booking sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm sticky top-24 overflow-hidden">
                <div class="bg-gradient-to-r {{ $color }} p-5">
                    <div class="text-white/80 text-xs font-medium mb-1">Reserve your spot</div>
                    <div class="text-white text-2xl font-extrabold">Book Seats</div>
                </div>
                <div class="p-6">
                    @auth
                        @if($event->available_seats > 0)
                            @if($errors->any())
                                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-3 py-2.5 rounded-xl text-sm mb-4">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('web.bookings.store') }}">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Seats</label>
                                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                                        <button type="button" onclick="var i=document.getElementById('seats');if(i.value>1)i.value--"
                                            class="px-4 py-3 text-gray-500 hover:bg-gray-50 font-bold text-lg transition">−</button>
                                        <input type="number" id="seats" name="seats_booked" min="1" max="{{ $event->available_seats }}"
                                            value="{{ old('seats_booked', 1) }}" required
                                            class="flex-1 text-center py-3 text-sm font-bold text-gray-800 focus:outline-none border-x border-gray-200">
                                        <button type="button" onclick="var i=document.getElementById('seats');if(i.value<{{ $event->available_seats }})i.value++"
                                            class="px-4 py-3 text-gray-500 hover:bg-gray-50 font-bold text-lg transition">+</button>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1.5">Max {{ $event->available_seats }} seats per booking</p>
                                </div>
                                <button type="submit"
                                    style="width:100%;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;font-weight:700;border-radius:0.875rem;padding:0.875rem;font-size:0.9375rem;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(124,58,237,0.35);">
                                    Confirm Booking
                                </button>
                                <p class="text-xs text-gray-400 text-center mt-3">Free cancellation anytime</p>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </div>
                                <p class="text-gray-700 font-semibold">Sold Out</p>
                                <p class="text-gray-400 text-sm mt-1">No seats available</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="w-14 h-14 bg-violet-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <p class="text-gray-700 font-semibold mb-1">Sign in to book</p>
                            <p class="text-gray-400 text-sm mb-4">Create a free account to reserve your seats</p>
                            <a href="{{ route('web.login') }}"
                                style="display:block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;font-weight:600;border-radius:0.875rem;padding:0.75rem;font-size:0.875rem;text-align:center;text-decoration:none;">
                                Sign In to Book
                            </a>
                            <a href="{{ route('web.register') }}" class="block text-center text-sm text-violet-600 hover:underline mt-2">
                                Create free account
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
