<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('organizations', OrganizationController::class);
Route::get('organizations/{id}/locations', [OrganizationController::class, 'getLocations']);

Route::apiResource('locations', LocationController::class);
Route::apiResource('devices', DeviceController::class);
Route::get('locations/{id}/devices', [LocationController::class, 'getDevices']);
Route::delete('locations/{id}/devices/{deviceId}', [LocationController::class, 'removeDevice']);
