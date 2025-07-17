<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

// Customer routes
Route::apiResource("customers", CustomerController::class);

// Table routes
Route::apiResource("tables", TableController::class);
Route::post("tables/{table}/check-availability", [TableController::class, "checkAvailability"]);

// Reservation routes
Route::apiResource("reservations", ReservationController::class);

