Hello {{ $booking->user->name ?? 'there' }},

Your booking has been confirmed.

Event: {{ $booking->event->title ?? 'N/A' }}
Seats Booked: {{ $booking->seats_booked }}
Booking Date: {{ $booking->booking_date }}
Status: {{ $booking->status }}

Thank you for your booking!
