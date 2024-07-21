<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/organizations', function () {
    return view('organizations.index');
});
Route::get('/organizations/{id}/locations', function ($id) {
    return view('locations.index', ['organization_id' => $id]);
});

Route::get('/locations/{id}/devices', function ($id) {
    return view('devices.index', ['location_id' => $id]);
});
