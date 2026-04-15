@extends('layouts.app')
@section('title', 'My Bookings — TicketFlow')
@section('content')

{{-- Page header --}}
<div style="background:#355872;padding:3rem 0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-white mb-1">My Bookings</h1>
        <p class="text-sm" style="color:#9CD5FF;">All your event reservations in one place</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Stats --}}
    @php
        $active    = $bookings->where('status', 'booked')->count();
        $cancelled = $bookings->where('status', 'cancelled')->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #dde8f0;">
            <div class="text-3xl font-extrabold mb-1" style="color:#355872;">{{ $bookings->total() }}</div>
            <div class="text-sm" style="color:#6b8fa8;">Total Bookings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #dde8f0;">
            <div class="text-3xl font-extrabold mb-1" style="color:#2a6a9a;">{{ $active }}</div>
            <div class="text-sm" style="color:#6b8fa8;">Active</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm" style="border:1px solid #dde8f0;">
            <div class="text-3xl font-extrabold mb-1" style="color:#8aacbf;">{{ $cancelled }}</div>
            <div class="text-sm" style="color:#6b8fa8;">Cancelled</div>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm text-center py-20 px-6" style="border:1px solid #dde8f0;">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#e0f4ff;">
                <svg class="w-10 h-10" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold mb-2" style="color:#355872;">No bookings yet</h3>
            <p class="text-sm mb-6" style="color:#6b8fa8;">Discover events and book your first experience</p>
            <a href="{{ route('web.events.index') }}"
                style="display:inline-flex;align-items:center;gap:.5rem;background:#355872;color:#fff;font-weight:600;border-radius:.875rem;padding:.75rem 1.5rem;font-size:.875rem;text-decoration:none;">
                Browse Events
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                @php
                    $banners = ['#355872','#7AAACE','#4a7a9b','#2a5f7a','#5a8fa8','#3d6e8a'];
                    $bg = $banners[$booking->event->id % count($banners)];
                @endphp
                <div class="bg-white rounded-2xl overflow-hidden flex flex-col sm:flex-row transition-all" style="border:1px solid #dde8f0;box-shadow:0 1px 4px rgba(53,88,114,.07);">
                    {{-- Colour strip --}}
                    <div class="w-full sm:w-1.5 flex-shrink-0 h-1.5 sm:h-auto" style="background:{{ $bg }};"></div>

                    <div class="flex-1 p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                        {{-- Date badge --}}
                        <div class="flex-shrink-0 w-14 h-14 rounded-xl flex flex-col items-center justify-center" style="background:#f0f7ff;border:1px solid #dde8f0;">
                            <div class="text-xs font-bold uppercase leading-none" style="color:#7AAACE;">
                                {{ \Carbon\Carbon::parse($booking->event->event_datetime)->format('M') }}
                            </div>
                            <div class="text-2xl font-extrabold leading-tight" style="color:#355872;">
                                {{ \Carbon\Carbon::parse($booking->event->event_datetime)->format('d') }}
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('web.events.show', $booking->event->id) }}"
                                class="text-base font-bold block truncate hover:underline" style="color:#1a2e3d;text-decoration:none;">
                                {{ $booking->event->title }}
                            </a>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5 text-xs" style="color:#6b8fa8;">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $booking->event->location }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \Carbon\Carbon::parse($booking->event->event_datetime)->format('H:i') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $booking->seats_booked }} seat{{ $booking->seats_booked > 1 ? 's' : '' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Booked {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        {{-- Status + action --}}
                        <div class="flex sm:flex-col items-center sm:items-end gap-3 flex-shrink-0">
                            @if($booking->status === 'booked')
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full" style="background:#e0f4ff;color:#2a6a9a;">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:#7AAACE;"></span>
                                    Confirmed
                                </span>
                                <form method="POST" action="{{ route('web.bookings.destroy', $booking->id) }}"
                                    onsubmit="return confirm('Cancel this booking?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-medium hover:underline transition-colors" style="color:#ef4444;">
                                        Cancel booking
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full" style="background:#f0f0f0;color:#6b7280;">
                                    Cancelled
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
