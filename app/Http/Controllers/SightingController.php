<?php

namespace App\Http\Controllers;

use App\Models\Sighting;
use Illuminate\Http\Request;

class SightingController extends Controller
{
    public function index()
    {
        return response()->json(Sighting::with('report')->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_id' => 'required|exists:reports,report_id|unique:sightings,report_id',
            'sighted_person' => 'nullable|string|max:255',
            'time_seen' => 'nullable|date',
        ]);

        try {
            $s = Sighting::create($data);
            return response()->json($s, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create sighting'], 400);
        }
    }

    public function show(Sighting $sighting)
    {
        return response()->json($sighting->load('report'));
    }

    public function update(Request $request, Sighting $sighting)
    {
        $data = $request->validate([
            'sighted_person' => 'nullable|string|max:255',
            'time_seen' => 'nullable|date',
        ]);

        try {
            $sighting->update($data);
            return response()->json($sighting);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update sighting'], 400);
        }
    }

    public function destroy(Sighting $sighting)
    {
        try {
            $sighting->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete sighting'], 400);
        }
    }
}
