<?php

use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\WebBookingController;
use App\Http\Controllers\Web\WebEventController;
use Illuminate\Support\Facades\Route;

// Home → redirect to events
Route::get('/', function () {
    return redirect()->route('web.events.index');
});

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login',     [WebAuthController::class, 'showLogin'])->name('web.login');
Route::post('/login',    [WebAuthController::class, 'login']);
Route::get('/register',  [WebAuthController::class, 'showRegister'])->name('web.register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout',   [WebAuthController::class, 'logout'])->name('web.logout')->middleware('auth');

// ── Events ────────────────────────────────────────────────────────────────────
Route::get('/events',              [WebEventController::class, 'index'])->name('web.events.index');
Route::get('/events/create',       [WebEventController::class, 'create'])->name('web.events.create')->middleware('auth');
Route::post('/events',             [WebEventController::class, 'store'])->name('web.events.store')->middleware('auth');
Route::get('/events/{event}',      [WebEventController::class, 'show'])->name('web.events.show');
Route::get('/events/{event}/edit', [WebEventController::class, 'edit'])->name('web.events.edit')->middleware('auth');
Route::put('/events/{event}',      [WebEventController::class, 'update'])->name('web.events.update')->middleware('auth');
Route::delete('/events/{event}',   [WebEventController::class, 'destroy'])->name('web.events.destroy')->middleware('auth');

// ── Bookings ──────────────────────────────────────────────────────────────────
Route::get('/bookings',              [WebBookingController::class, 'index'])->name('web.bookings.index')->middleware('auth');
Route::post('/bookings',             [WebBookingController::class, 'store'])->name('web.bookings.store')->middleware('auth');
Route::delete('/bookings/{booking}', [WebBookingController::class, 'destroy'])->name('web.bookings.destroy')->middleware('auth');
