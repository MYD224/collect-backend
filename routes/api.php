<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\Booking\BookingController;
use App\Interfaces\Http\Controllers\Posts\PostController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post("/bookings", [BookingController::class, 'store']);

Route::get("/tickets", fn() => "List of tickets updated 17.");

Route::resource("posts", PostController::class);
