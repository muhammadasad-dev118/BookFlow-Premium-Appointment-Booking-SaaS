<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\BookingSuccessController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'throttle:bookings'], function () {
    Route::get('/availability', [BookingController::class, 'getAvailability']);
    Route::post('/book', [BookingController::class, 'book']);
});

Route::get('/booking/success', [BookingSuccessController::class, 'success']);
Route::get('/booking/cancel', [BookingSuccessController::class, 'cancel']);

// Stripe webhook endpoint (should bypass CSRF in web middleware, or be in API middleware)
Route::post('/stripe/webhook/booking', [StripeWebhookController::class, 'handleWebhook']);
