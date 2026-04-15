<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WebBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('event')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id'     => 'required|exists:events,id',
            'seats_booked' => 'required|integer|min:1',
        ]);

        try {
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

            return redirect()->route('web.bookings.index')->with('success', 'Booking confirmed!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->update(['status' => 'cancelled']);
        $booking->event()->increment('available_seats', $booking->seats_booked);

        return redirect()->route('web.bookings.index')->with('success', 'Booking cancelled.');
    }
}
