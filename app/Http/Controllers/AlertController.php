<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        return response()->json(Alert::with(['caseFile', 'approvedByOfficer'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'case_id' => 'required|exists:cases,case_id',
            'alert_type' => 'required|string|in:amber,silver,red,yellow',
            'status' => 'nullable|string|in:active,expired,cancelled',
            'approved_by' => 'nullable|exists:officers,officer_id',
            'expires_at' => 'nullable|date',
            'message' => 'nullable|string',
        ]);

        try {
            $a = Alert::create($data);
            return response()->json($a, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create alert'], 400);
        }
    }

    public function show(Alert $alert)
    {
        return response()->json($alert->load(['caseFile', 'approvedByOfficer']));
    }

    public function update(Request $request, Alert $alert)
    {
        $data = $request->validate([
            'alert_type' => 'sometimes|string|in:amber,silver,red,yellow',
            'status' => 'nullable|string|in:active,expired,cancelled',
            'approved_by' => 'nullable|exists:officers,officer_id',
            'expires_at' => 'nullable|date',
            'message' => 'nullable|string',
        ]);

        try {
            $alert->update($data);
            return response()->json($alert);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update alert'], 400);
        }
    }

    public function destroy(Alert $alert)
    {
        try {
            $alert->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete alert'], 400);
        }
    }
}
