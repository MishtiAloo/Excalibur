<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Request;

class TipController extends Controller
{
    public function index()
    {
        return response()->json(Tip::with(['report', 'verifiedBy'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_id' => 'required|exists:reports,report_id|unique:tips,report_id',
            'credibility_score' => 'nullable|integer|min:0|max:100',
            'verified_by' => 'nullable|exists:officers,officer_id',
        ]);

        try {
            $t = Tip::create($data);
            return response()->json($t, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create tip'], 400);
        }
    }

    public function show(Tip $tip)
    {
        return response()->json($tip->load(['report', 'verifiedBy']));
    }

    public function update(Request $request, Tip $tip)
    {
        $data = $request->validate([
            'credibility_score' => 'nullable|integer|min:0|max:100',
            'verified_by' => 'nullable|exists:officers,officer_id',
        ]);

        try {
            $tip->update($data);
            return response()->json($tip);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update tip'], 400);
        }
    }

    public function destroy(Tip $tip)
    {
        try {
            $tip->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete tip'], 400);
        }
    }
}
