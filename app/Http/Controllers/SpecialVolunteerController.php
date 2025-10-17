<?php

namespace App\Http\Controllers;

use App\Models\SpecialVolunteer;
use Illuminate\Http\Request;

class SpecialVolunteerController extends Controller
{
    public function index()
    {
        return response()->json(SpecialVolunteer::with(['volunteer', 'verifiedByOfficer'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'special_volunteer_id' => 'required|exists:volunteers,volunteer_id|unique:special_volunteers,special_volunteer_id',
            'terrain_type' => 'required|string|in:water,forest,hilltrack,urban',
            'verified_by_officer' => 'nullable|exists:officers,officer_id',
        ]);

        try {
            $sv = SpecialVolunteer::create($data);
            return response()->json($sv, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create special volunteer'], 400);
        }
    }

    public function show(SpecialVolunteer $special_volunteer)
    {
        return response()->json($special_volunteer->load(['volunteer', 'verifiedByOfficer']));
    }

    public function update(Request $request, SpecialVolunteer $special_volunteer)
    {
        $data = $request->validate([
            'terrain_type' => 'sometimes|string|in:water,forest,hilltrack,urban',
            'verified_by_officer' => 'nullable|exists:officers,officer_id',
        ]);

        try {
            $special_volunteer->update($data);
            return response()->json($special_volunteer);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update special volunteer'], 400);
        }
    }

    public function destroy(SpecialVolunteer $special_volunteer)
    {
        try {
            $special_volunteer->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete special volunteer'], 400);
        }
    }
}
