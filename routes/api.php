<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::apiResource('organizations', OrganizationController::class);
Route::apiResource('locations', LocationController::class);
Route::apiResource('devices', DeviceController::class);