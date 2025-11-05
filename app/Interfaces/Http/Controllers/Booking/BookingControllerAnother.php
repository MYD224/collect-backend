<?php
namespace App\Interfaces\Http\Controllers\Booking;


use App\Interfaces\Http\Controllers\Controller;


class BookingControllerAnother extends Controller
{

    public function another() {
        return response()->json(["ok" => "anoh"]);
    }
}