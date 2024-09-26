<?php

use App\Http\Controllers\API\GuestController;
use Illuminate\Support\Facades\Route;

Route::apiResource('guests', GuestController::class);
