<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        return response()->json(Organization::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unique_code' => 'required|unique:organizations',
            'name' => 'required'
        ]);

        $organization = Organization::create($validated);

        return response()->json($organization, 201);
    }

    public function show($id)
    {
        $organization = Organization::findOrFail($id);
        return response()->json($organization);
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $validated = $request->validate([
            'unique_code' => 'required|unique:organizations,unique_code,' . $id,
            'name' => 'required'
        ]);

        $organization->update($validated);

        return response()->json($organization);
    }

    public function destroy($id)
    {
        Organization::destroy($id);
        return response()->json(null, 204);
    }

    public function getLocations($id)
    {
        $organization = Organization::with('locations.devices')->findOrFail($id);
        return response()->json($organization->locations);
    }
}