<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $location = Location::create($validated + [
            'is_active' => true,
        ]);

        return response()->json([
            'id' => $location->id,
            'name' => $location->name,
        ]);
    }
}
