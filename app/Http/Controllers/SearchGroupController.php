<?php

namespace App\Http\Controllers;

use App\Models\SearchGroup;
use Illuminate\Http\Request;

class SearchGroupController extends Controller
{
    public function index()
    {
        return response()->json(SearchGroup::with(['caseFile','leader'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'case_id' => 'required|exists:cases,case_id',
            'leader_id' => 'required|exists:users,id',
            'type' => 'required|string|in:citizen,covert,terrainSpecial',
            'intensity' => 'nullable|string|in:basic,rigorous,extreme',
            'status' => 'nullable|string|in:active,paused,completed',
            'allocated_time' => 'nullable|integer|min:0',
            'instruction' => 'nullable|string',
            'allocated_lat' => 'nullable|numeric|between:-90,90',
            'allocated_lng' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:0',
        ]);
        try {
            $sg = SearchGroup::create($data);
            return response()->json($sg, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create group'], 400);
        }
    }

    public function show(SearchGroup $search_group)
    {
        return response()->json($search_group->load(['caseFile','leader','volunteers']));
    }

    public function update(Request $request, SearchGroup $search_group)
    {
        $data = $request->validate([
            'type' => 'sometimes|string|in:citizen,covert,terrainSpecial',
            'intensity' => 'nullable|string|in:basic,rigorous,extreme',
            'status' => 'nullable|string|in:active,paused,completed',
            'allocated_time' => 'nullable|integer|min:0',
            'instruction' => 'nullable|string',
            'allocated_lat' => 'nullable|numeric|between:-90,90',
            'allocated_lng' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:0',
        ]);
        try {
            $search_group->update($data);
            return response()->json($search_group);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update group'], 400);
        }
    }

    public function destroy(SearchGroup $search_group)
    {
        try {
            $search_group->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete group'], 400);
        }
    }
}
