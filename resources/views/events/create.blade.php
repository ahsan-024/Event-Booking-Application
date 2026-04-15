@extends('layouts.app')
@section('title', 'Create Event — TicketFlow')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
    <div class="mb-6">
        <a href="{{ route('web.events.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-violet-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Events
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);padding:1.5rem 1.75rem;">
            <h1 class="text-2xl font-bold text-white">Create New Event</h1>
            <p class="text-purple-200 text-sm mt-1">Fill in the details to publish your event</p>
        </div>

        <div class="p-6 sm:p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6">
                    <div class="font-semibold mb-1">Please fix the following errors:</div>
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('web.events.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Annual Tech Summit 2026"
                        style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" placeholder="Tell attendees what to expect..."
                        style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;resize:vertical;">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required placeholder="e.g. Madison Square Garden, New York"
                        style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date & Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="event_datetime" value="{{ old('event_datetime') }}" required
                            style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Total Seats <span class="text-red-500">*</span></label>
                        <input type="number" name="total_seats" value="{{ old('total_seats') }}" min="1" required placeholder="e.g. 200"
                            style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        style="flex:1;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;font-weight:600;border-radius:0.75rem;padding:0.875rem;font-size:0.9375rem;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(124,58,237,0.3);">
                        Publish Event
                    </button>
                    <a href="{{ route('web.events.index') }}"
                        style="padding:0.875rem 1.5rem;border-radius:0.75rem;font-size:0.875rem;font-weight:500;color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;text-decoration:none;display:inline-flex;align-items:center;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
