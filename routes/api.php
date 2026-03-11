<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AvailabilityController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API de disponibilidad de doctores
Route::get('/appointments/availability', [AvailabilityController::class, 'getSlots']);
