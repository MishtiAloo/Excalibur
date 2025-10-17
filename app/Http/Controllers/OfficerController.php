<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        return response()->json(Officer::with('user')->paginate(10));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'officer_id' => 'required|exists:users,id|unique:officers,officer_id',
            'badge_no' => 'required|string|max:64',
            'department' => 'nullable|string|max:128',
            'rank' => 'nullable|string|max:64',
        ]);

        try {
            $o = Officer::create($data);
            return response()->json($o, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create officer'], 400);
        }
    }

    public function show(Officer $officer)
    {
        return response()->json($officer->load(['user', 'instructions', 'verifiedSpecialVolunteers', 'alerts']));
    }

    public function update(Request $request, Officer $officer)
    {
        $data = $request->validate([
            'badge_no' => 'sometimes|string|max:64',
            'department' => 'nullable|string|max:128',
            'rank' => 'nullable|string|max:64',
        ]);

        try {
            $officer->update($data);
            return response()->json($officer);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update officer'], 400);
        }
    }

    public function destroy(Officer $officer)
    {
        try {
            $officer->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete officer'], 400);
        }
    }
}
