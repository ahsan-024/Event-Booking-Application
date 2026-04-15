<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('event')
            ->where('user_id', auth()->id())
            ->paginate(15);

        return response()->json($bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = DB::transaction(function () use ($request) {
            $event = Event::lockForUpdate()->findOrFail($request->event_id);

            if ($event->available_seats < $request->seats_booked) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'seats_booked' => ['Not enough available seats.'],
                ]);
            }

            $booking = Booking::create([
                'user_id'      => auth()->id(),
                'event_id'     => $event->id,
                'seats_booked' => $request->seats_booked,
                'status'       => 'booked',
                'booking_date' => now(),
            ]);

            $event->decrement('available_seats', $request->seats_booked);

            return $booking;
        });

        Mail::to(auth()->user())->send(new BookingConfirmationMail($booking));

        return response()->json($booking->load('event'), 201);
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->update(['status' => 'cancelled']);
        $booking->event()->increment('available_seats', $booking->seats_booked);

        return response()->noContent();
    }
}
