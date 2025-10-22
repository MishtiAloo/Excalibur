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
            'vetting_status' => 'nullable|string|in:pending,approved,rejected',
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
            'vetting_status' => 'sometimes|string|in:pending,approved,rejected',
            'verified_by_officer' => 'nullable|exists:officers,officer_id',
        ]);

        // For verify buttons
        if ($request->action === 'approve') {
            $data['vetting_status'] = 'approved';
        } elseif ($request->action === 'reject') {
            $data['vetting_status'] = 'rejected';
        }

        // Update role
        if ($special_volunteer->volunteer->user) {
            $special_volunteer->volunteer->user->role = 'specialVolunteer';
            $special_volunteer->volunteer->user->save();
        }

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

    public function applySpecialVolunteer(Request $request)
    {
        $data = $request->validate([
            'volunteer_id' => 'required|exists:volunteers,volunteer_id|unique:special_volunteers,special_volunteer_id',
            'terrain_type' => 'required|string|in:water,forest,hilltrack,urban',
        ]);

        try {
            $data['vetting_status'] = 'pending';
            $sv = SpecialVolunteer::create([
                'special_volunteer_id' => $data['volunteer_id'],
                'terrain_type' => $data['terrain_type'],
                'vetting_status' => $data['vetting_status'],
                'verified_by_officer' => null,
            ]);
            return redirect()->back()->with('success', 'Special Volunteer application submitted successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to apply as Special Volunteer']);
        }
    }
}
