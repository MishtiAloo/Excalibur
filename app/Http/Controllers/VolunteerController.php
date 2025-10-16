<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index()
    {
        return response()->json(Volunteer::with(['user', 'specialVolunteer'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'volunteer_id' => 'required|exists:users,id|unique:volunteers,volunteer_id',
            'vetting_status' => 'nullable|string|in:pending,approved,rejected',
            'availability' => 'nullable|string|in:available,busy,inactive',
        ]);

        try {
            $v = Volunteer::create($data);
            return response()->json($v, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create volunteer'], 400);
        }
    }

    public function show(Volunteer $volunteer)
    {
        return response()->json($volunteer->load(['user', 'specialVolunteer', 'searchGroups']));
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $data = $request->validate([
            'vetting_status' => 'nullable|string|in:pending,approved,rejected',
            'availability' => 'nullable|string|in:available,busy,inactive',
        ]);

        try {
            $volunteer->update($data);
            return response()->json($volunteer);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update volunteer'], 400);
        }
    }

    public function destroy(Volunteer $volunteer)
    {
        try {
            $volunteer->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete volunteer'], 400);
        }
    }
}
