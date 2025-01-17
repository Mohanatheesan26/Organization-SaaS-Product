<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeviceController extends Controller
{
    public function index()
    {
        return response()->json(Device::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'location_id' => 'required|exists:locations,id',
                'unique_number' => 'required|integer|unique:devices',
                'type' => 'required|in:pos,kiosk,digital signage',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive'
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('device_images', 'public');
                $validated['image'] = $imagePath;
            }

            $location = Location::find($validated['location_id']);
            if ($location->devices()->count() >= 10) {
                return response()->json(['error' => 'A location cannot have more than 10 devices'], 400);
            }

            $validated['date_created'] = now();

            $device = Device::create($validated);

            return response()->json($device, 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
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
            'unique_number' => 'required|integer|unique:devices,unique_number,' . $id,
            'type' => 'required|in:pos,kiosk,digital signage',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('device_images', 'public');
            $validated['image'] = $imagePath;
        }

        $device->update($validated);

        return response()->json($device);
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return response()->json(['message' => 'Device removed successfully'], 204);
    }
}
