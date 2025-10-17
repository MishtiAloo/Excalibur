<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use Illuminate\Http\Request;

class CaseFileController extends Controller
{
    public function index()
    {
        return response()->json(CaseFile::with('creator')->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'created_by' => 'required|exists:users,id',
            'case_type' => 'required|string|in:missing,wanted,hazard,attack',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coverage_lat' => 'nullable|numeric|between:-90,90',
            'coverage_lng' => 'nullable|numeric|between:-180,180',
            'status' => 'nullable|string|in:active,under_investigation,resolved,closed',
            'urgency' => 'nullable|string|in:low,medium,high,critical,national',
        ]);
        try {
            $case = CaseFile::create($data);
            return response()->json($case, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create case'], 400);
        }
    }

    public function show(CaseFile $case)
    {
        return response()->json($case->load(['creator','searchGroups']));
    }

    public function update(Request $request, CaseFile $case)
    {
        $data = $request->validate([
            'case_type' => 'sometimes|string|in:missing,wanted,hazard,attack',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'coverage_lat' => 'nullable|numeric|between:-90,90',
            'coverage_lng' => 'nullable|numeric|between:-180,180',
            'status' => 'nullable|string|in:active,under_investigation,resolved,closed',
            'urgency' => 'nullable|string|in:low,medium,high,critical,national',
        ]);
        try {
            $case->update($data);
            return response()->json($case);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update case'], 400);
        }
    }

    public function destroy(CaseFile $case)
    {
        try {
            $case->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete case'], 400);
        }
    }
}
