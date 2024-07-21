<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Location;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        return response()->json(Device::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'unique_number' => 'required|unique:devices',
            'type' => 'required|in:pos,kiosk,digital signage',
            'image' => 'required|url',
            'status' => 'required|in:active,inactive'
        ]);

        $location = Location::find($validated['location_id']);
        if ($location->devices()->count() >= 10) {
            return response()->json(['error' => 'A location cannot have more than 10 devices'], 400);
        }

        $validated['date_created'] = now();

        $device = Device::create($validated);
        
        return response()->json($device, 201);
    }

    public function show($id)
    {
        $device = Device::findOrFail($id);
        return response()->json($device);
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $validated = $request->validate([
            'unique_number' => 'required|unique:devices,unique_number,' . $id,
            'type' => 'required|in:pos,kiosk,digital signage',
            'image' => 'required|url',
            'status' => 'required|in:active,inactive'
        ]);

        $device->update($validated);

        return response()->json($device);
    }

    public function destroy($id)
    {
        Device::destroy($id);
        return response()->json(null, 204);
    }
}
