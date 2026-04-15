@extends('layouts.app')
@section('title', 'Edit Event — TicketFlow')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
    <div class="mb-6">
        <a href="{{ route('web.events.show', $event->id) }}" class="inline-flex items-center gap-1.5 text-sm hover:underline" style="color:#355872;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Event
        </a>
    </div>

    <div class="bg-white rounded-2xl overflow-hidden shadow-sm" style="border:1px solid #dde8f0;">
        <div style="background:#2a4660;padding:1.5rem 1.75rem;">
            <h1 class="text-2xl font-bold text-white">Edit Event</h1>
            <p class="text-sm mt-1" style="color:#9CD5FF;">Update the details for "{{ $event->title }}"</p>
        </div>

        <div class="p-6 sm:p-8">
            @if($errors->any())
                <div class="px-4 py-3 rounded-xl text-sm mb-6" style="background:#fde8e8;border:1px solid #f5b8b8;color:#7f1d1d;">
                    <div class="font-semibold mb-1">Please fix the following errors:</div>
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('web.events.update', $event->id) }}" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                        style="width:100%;background:#F7F8F0;border:1.5px solid #c5d8e8;border-radius:.75rem;padding:.875rem 1rem;font-size:.875rem;color:#1a2e3d;box-sizing:border-box;">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Description</label>
                    <textarea name="description" rows="4"
                        style="width:100%;background:#F7F8F0;border:1.5px solid #c5d8e8;border-radius:.75rem;padding:.875rem 1rem;font-size:.875rem;color:#1a2e3d;box-sizing:border-box;resize:vertical;">{{ old('description', $event->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Location</label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}" required
                        style="width:100%;background:#F7F8F0;border:1.5px solid #c5d8e8;border-radius:.75rem;padding:.875rem 1rem;font-size:.875rem;color:#1a2e3d;box-sizing:border-box;">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Date & Time</label>
                        <input type="datetime-local" name="event_datetime"
                            value="{{ old('event_datetime', \Carbon\Carbon::parse($event->event_datetime)->format('Y-m-d\TH:i')) }}" required
                            style="width:100%;background:#F7F8F0;border:1.5px solid #c5d8e8;border-radius:.75rem;padding:.875rem 1rem;font-size:.875rem;color:#1a2e3d;box-sizing:border-box;">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Total Seats</label>
                        <input type="number" name="total_seats" value="{{ old('total_seats', $event->total_seats) }}" min="1" required
                            style="width:100%;background:#F7F8F0;border:1.5px solid #c5d8e8;border-radius:.75rem;padding:.875rem 1rem;font-size:.875rem;color:#1a2e3d;box-sizing:border-box;">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        style="flex:1;background:#355872;color:#fff;font-weight:600;border-radius:.75rem;padding:.875rem;font-size:.9375rem;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(53,88,114,.25);"
                        onmouseover="this.style.background='#2a4660'"
                        onmouseout="this.style.background='#355872'">
                        Save Changes
                    </button>
                    <a href="{{ route('web.events.show', $event->id) }}"
                        style="padding:.875rem 1.5rem;border-radius:.75rem;font-size:.875rem;font-weight:500;color:#355872;background:#F7F8F0;border:1.5px solid #c5d8e8;text-decoration:none;display:inline-flex;align-items:center;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
