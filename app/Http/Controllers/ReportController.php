<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json(Report::with(['caseFile','user'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'case_id' => 'required|exists:cases,case_id',
            'user_id' => 'required|exists:users,id',
            'report_type' => 'required|string|in:tip,evidence,sighting,hazard,attack,general',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'timestamp' => 'nullable|date',
            'status' => 'nullable|string|in:pending,verified,ressponded,falsed,dismissed',
        ]);
        try {
            $r = Report::create($data);
            return response()->json($r, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create report'], 400);
        }
    }

    public function show(Report $report)
    {
        return response()->json($report->load(['caseFile','user','media']));
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'report_type' => 'sometimes|string|in:tip,evidence,sighting,hazard,attack,general',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'timestamp' => 'nullable|date',
            'status' => 'nullable|string|in:pending,verified,ressponded,falsed,dismissed',
        ]);
        try {
            $report->update($data);
            return response()->json($report);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update report'], 400);
        }
    }

    public function destroy(Report $report)
    {
        try {
            $report->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete report'], 400);
        }
    }
}
