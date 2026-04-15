# TicketFlow — Event Booking Application

A full-stack event booking platform built with **Laravel 10**, **MySQL**, and **Blade + Tailwind CSS**. Users can browse events, book seats, and manage their reservations. Event organizers can create, update, and delete events. The system enforces seat availability atomically to prevent overbooking.

---

## Features

- **User authentication** — register, login, logout (session-based)
- **Event management** — create, read, update, delete events with capacity control
- **Seat booking** — atomic booking with `DB::transaction()` + `lockForUpdate()` to prevent race conditions
- **Booking management** — view and cancel your own bookings with automatic seat restoration
- **Filters** — search events by date and location
- **Email notifications** — booking confirmation emails via Laravel Mail
- **Property-based testing** — 16 correctness properties verified with [Eris](https://github.com/giorgiosironi/eris)
- **Responsive UI** — Blade templates styled with Tailwind CSS CDN

---

## Tech Stack

| Layer       | Technology                        |
|-------------|-----------------------------------|
| Backend     | PHP 8.2, Laravel 10               |
| Frontend    | Blade templates, Tailwind CSS CDN |
| Database    | MySQL 8.0                         |
| Testing     | PHPUnit 10, Eris (PBT)            |
| Runtime     | Docker, Apache                    |

---

## Requirements

- [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/)
- Git

> No local PHP or Composer installation required — everything runs inside Docker.

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/event-booking-app.git
cd event-booking-app
```

### 2. Set up environment file

```bash
cp .env.example .env
```

### 3. Build and start Docker containers

```bash
docker compose up --build -d
```

This starts two containers:

| Container           | Service       | Port         |
|---------------------|---------------|--------------|
| `event-booking-app` | Laravel/Apache | `8003 → 80`  |
| `event-booking-db`  | MySQL 8.0      | `3309 → 3306`|

### 4. Generate application key

```bash
docker exec event-booking-app php artisan key:generate
```

---

## Environment Setup

The `.env.example` file contains all required variables. Key values for the Docker setup:

```dotenv
APP_URL=http://localhost:8003

DB_CONNECTION=mysql
DB_HOST=db          # Docker service name — do not change
DB_PORT=3306
DB_DATABASE=event_booking
DB_USERNAME=event_user
DB_PASSWORD=secret

MAIL_MAILER=log     # Emails are written to storage/logs/laravel.log
```

> **Note:** `DB_HOST=db` refers to the Docker Compose service name. If running outside Docker, change this to `127.0.0.1` and update the port to `3309`.

---

## Database Migration

Run all migrations to create the database schema:

```bash
docker exec event-booking-app php artisan migrate
```

This creates the following tables:

| Table                        | Description                          |
|------------------------------|--------------------------------------|
| `users`                      | Registered users                     |
| `password_reset_tokens`      | Password reset tokens                |
| `personal_access_tokens`     | Sanctum tokens (reserved)            |
| `events`                     | Events with seat capacity            |
| `bookings`                   | Seat reservations per user/event     |

To reset and re-run all migrations from scratch:

```bash
docker exec event-booking-app php artisan migrate:fresh
```

---

## Seeder Usage

The `EventSeeder` creates sample events for development and testing.

**Run all seeders:**

```bash
docker exec event-booking-app php artisan db:seed
```

**Run a specific seeder:**

```bash
docker exec event-booking-app php artisan db:seed --class=EventSeeder
```

**Fresh migration + seed in one command:**

```bash
docker exec event-booking-app php artisan migrate:fresh --seed
```

---

## Running Tests

Tests use an **SQLite in-memory database** — no MySQL connection required.

**Run the full test suite:**

```bash
docker exec event-booking-app php artisan test --no-coverage
```

Or using the host PHP (if PHP 8.2 is installed locally):

```bash
php8.2 artisan test --no-coverage
```

**Run a specific test file:**

```bash
docker exec event-booking-app php artisan test tests/Feature/BookingFlowTest.php --no-coverage
```

### Test Coverage

| Test Class                          | Type     | Property / Scenario                              |
|-------------------------------------|----------|--------------------------------------------------|
| `AuthTest`                          | Feature  | Register, login, logout, wrong password          |
| `EventCrudTest`                     | Feature  | Create, read, update, delete events              |
| `EventDeleteCascadeTest`            | Feature  | Cascade delete removes bookings                  |
| `BookingFlowTest`                   | Feature  | Full booking and cancellation flow               |
| `NotificationTest`                  | Feature  | Confirmation email sent on booking               |
| `ValidationErrorFormatTest`         | Feature  | 422 responses contain structured errors          |
| `NewEventSeatsPropertyTest`         | PBT      | Property 2: available_seats = total_seats        |
| `EventCreatorPropertyTest`          | PBT      | Property 3: created_by = auth user               |
| `EventValidationPropertyTest`       | PBT      | Property 4: required fields enforced             |
| `EventListFieldsPropertyTest`       | PBT      | Property 5: list response fields                 |
| `EventFilterPropertyTest`           | PBT      | Property 6: filters return matching events       |
| `EventUpdateRoundTripPropertyTest`  | PBT      | Property 7: update persists new values           |
| `BookingRecordIntegrityPropertyTest`| PBT      | Property 8: booking record integrity             |
| `BookingDecrementsSeatsPropertyTest`| PBT      | Property 9: booking decrements seats             |
| `OverbookingRejectionPropertyTest`  | PBT      | Property 10: overbooking rejected                |
| `BookingOwnershipPropertyTest`      | PBT      | Property 11: users see only own bookings         |
| `BookingListFieldsPropertyTest`     | PBT      | Property 12: booking list response fields        |
| `CancellationRoundTripPropertyTest` | PBT      | Property 13: cancellation restores seats         |
| `CancelOwnershipPropertyTest`       | PBT      | Property 14: users cancel only own bookings      |
| `InvalidSeatCountPropertyTest`      | PBT      | Property 15: invalid seat count rejected         |
| `UnauthenticatedBookingPropertyTest`| PBT      | Property 1: unauthenticated access denied        |
| `ConcurrentBookingPropertyTest`     | PBT      | Property 16: concurrent bookings safe            |

---

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/                  # Blade UI controllers
│   │   │   │   ├── WebAuthController.php
│   │   │   │   ├── WebEventController.php
│   │   │   │   └── WebBookingController.php
│   │   │   ├── AuthController.php    # JSON API (legacy)
│   │   │   ├── EventController.php
│   │   │   └── BookingController.php
│   │   └── Requests/                 # Form Request validators
│   ├── Mail/
│   │   └── BookingConfirmationMail.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Event.php
│   │   └── Booking.php
│   ├── Policies/
│   │   └── BookingPolicy.php         # Ownership enforcement
│   └── Providers/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│       └── EventSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/
│   ├── events/
│   └── bookings/
├── routes/
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── docker/
├── docker-compose.yml
├── Dockerfile
└── phpunit.xml
```

---

## Routes

| Method | URI                    | Auth | Description              |
|--------|------------------------|------|--------------------------|
| GET    | `/`                    | —    | Redirect to events       |
| GET    | `/events`              | —    | Browse events            |
| GET    | `/events/create`       | ✓    | Create event form        |
| POST   | `/events`              | ✓    | Store new event          |
| GET    | `/events/{id}`         | —    | Event detail             |
| GET    | `/events/{id}/edit`    | ✓    | Edit event form          |
| PUT    | `/events/{id}`         | ✓    | Update event             |
| DELETE | `/events/{id}`         | ✓    | Delete event             |
| GET    | `/bookings`            | ✓    | My bookings              |
| POST   | `/bookings`            | ✓    | Book seats               |
| DELETE | `/bookings/{id}`       | ✓    | Cancel booking           |
| GET    | `/login`               | —    | Login page               |
| POST   | `/login`               | —    | Authenticate             |
| GET    | `/register`            | —    | Register page            |
| POST   | `/register`            | —    | Create account           |
| POST   | `/logout`              | ✓    | Sign out                 |

---

## Stopping the Application

```bash
docker compose down
```

To also remove the database volume (all data):

```bash
docker compose down -v
```

---

## Test Login Credentials

A test account is pre-seeded in the database for reviewer access:

| Field    | Value                       |
|----------|-----------------------------|
| Email    | mughalahsan718@gmail.com    |
| Password | 11111111                    |

> Visit **http://localhost:8003/login** and use the credentials above to sign in immediately without registering.

---

## Application Flow

### How users interact with the system

1. **Browse events** — The home page (`/events`) is publicly accessible. Anyone can view the event listing and filter by date or location without logging in.
2. **Register / Sign in** — To book seats or create events, users must create a free account at `/register` or sign in at `/login`.
3. **View event details** — Clicking any event card opens the detail page showing full info, seat availability, and the booking form.
4. **Manage bookings** — Authenticated users can view all their reservations at `/bookings` and cancel any active booking.
5. **Create & manage events** — Authenticated users can create new events via the "Create Event" button. Only the event creator sees the Edit / Delete controls on the event detail page.

### How event booking works

```
User selects an event  →  Enters seat count  →  Submits booking form
        ↓
  DB transaction starts
        ↓
  Event row locked with lockForUpdate()
        ↓
  available_seats checked  ──(insufficient)──→  422 error returned, transaction rolled back
        ↓ (sufficient)
  Booking record created (status = booked)
        ↓
  available_seats decremented by seats_booked
        ↓
  Transaction committed  →  Confirmation email sent  →  Redirect to My Bookings
```

### How seat availability is handled

- When an event is **created**, `available_seats` is set equal to `total_seats`.
- Each **successful booking** decrements `available_seats` by the number of seats booked.
- Each **cancellation** increments `available_seats` back by the seats that were booked, restoring availability.
- **Overbooking is prevented** at the database level using `DB::transaction()` combined with `Event::lockForUpdate()`. This acquires a row-level exclusive lock on the event row for the duration of the transaction, serialising concurrent requests and ensuring `available_seats` never goes below zero — even under simultaneous load.
- The event detail page shows a **live availability bar** indicating the percentage of seats remaining, colour-coded green → amber → red as capacity fills up.

---

## License

Ahsan Mughal