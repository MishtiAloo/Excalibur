<?php

namespace App\Http\Controllers;

use App\Models\ResourceItem;
use Illuminate\Http\Request;

class ResourceItemController extends Controller
{
    public function index()
    {
        return response()->json(ResourceItem::paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stored_lat' => 'nullable|numeric|between:-90,90',
            'stored_lng' => 'nullable|numeric|between:-180,180',
            'condition' => 'nullable|string|in:new,good,moderate,old',
            'availability' => 'nullable|string|in:available,in_use,delayed_checkout,under_maintenance',
            'count' => 'nullable|integer|min:0',
            'availableCount' => 'nullable|integer|min:0',
        ]);

        try {
            $r = ResourceItem::create($data);
            return response()->json($r, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create resource'], 400);
        }
    }

    public function show(ResourceItem $resource)
    {
        return response()->json($resource);
    }

    public function update(Request $request, ResourceItem $resource)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'stored_lat' => 'nullable|numeric|between:-90,90',
            'stored_lng' => 'nullable|numeric|between:-180,180',
            'condition' => 'nullable|string|in:new,good,moderate,old',
            'availability' => 'nullable|string|in:available,in_use,delayed_checkout,under_maintenance',
            'count' => 'nullable|integer|min:0',
            'availableCount' => 'nullable|integer|min:0',
        ]);

        try {
            $resource->update($data);
            return response()->json($resource);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update resource'], 400);
        }
    }

    public function destroy(ResourceItem $resource)
    {
        try {
            $resource->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete resource'], 400);
        }
    }
}
