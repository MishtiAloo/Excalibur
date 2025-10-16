<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
    public function index()
    {
        return response()->json(Evidence::with(['report', 'receivedBy'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_id' => 'required|exists:reports,report_id|unique:evidences,report_id',
            'received' => 'nullable|boolean',
            'received_by' => 'nullable|exists:officers,officer_id',
        ]);

        try {
            $e = Evidence::create($data);
            return response()->json($e, 201);
        } catch (\Throwable $ex) {
            return response()->json(['error' => 'Failed to create evidence'], 400);
        }
    }

    public function show(Evidence $evidence)
    {
        return response()->json($evidence->load(['report', 'receivedBy']));
    }

    public function update(Request $request, Evidence $evidence)
    {
        $data = $request->validate([
            'received' => 'nullable|boolean',
            'received_by' => 'nullable|exists:officers,officer_id',
        ]);

        try {
            $evidence->update($data);
            return response()->json($evidence);
        } catch (\Throwable $ex) {
            return response()->json(['error' => 'Failed to update evidence'], 400);
        }
    }

    public function destroy(Evidence $evidence)
    {
        try {
            $evidence->delete();
            return response()->json(null, 204);
        } catch (\Throwable $ex) {
            return response()->json(['error' => 'Failed to delete evidence'], 400);
        }
    }
}
