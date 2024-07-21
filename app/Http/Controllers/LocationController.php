<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Organization;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return response()->json(Location::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'serial_number' => 'required|unique:locations',
            'name' => 'required',
            'ipv4_address' => 'required|ipv4'
        ]);

        $organization = Organization::find($validated['organization_id']);
        if ($organization->locations()->count() >= 5) {
            return response()->json(['error' => 'An organization cannot have more than 5 locations'], 400);
        }

        $location = Location::create($validated);
        return response()->json($location, 201);
    }

    public function show($id)
    {
        $location = Location::with('devices')->findOrFail($id);
        return response()->json($location);
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $validated = $request->validate([
            'serial_number' => 'required|unique:locations,serial_number,' . $id,
            'name' => 'required',
            'ipv4_address' => 'required|ipv4'
        ]);

        $location->update($validated);
        
        return response()->json($location);
    }

    public function destroy($id)
    {
        Location::destroy($id);
        return response()->json(null, 204);
    }

    public function getDevices($id)
    {
        $location = Location::with('devices')->findOrFail($id);
        return response()->json($location->devices);
    }

    public function removeDevice($id, $deviceId)
    {
        $location = Location::findOrFail($id);
        $location->devices()->findOrFail($deviceId)->delete();
        return response()->json(null, 204);
    }
}
