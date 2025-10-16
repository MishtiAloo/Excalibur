<?php

namespace App\Http\Controllers;

use App\Models\ResourceBooking;
use Illuminate\Http\Request;

class ResourceBookingController extends Controller
{
    public function index()
    {
        return response()->json(ResourceBooking::with(['resource', 'group', 'checkedOutBy'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'resource_id' => 'required|exists:resources,resource_id',
            'group_id' => 'required|exists:search_groups,group_id',
            'checked_out_by' => 'required|exists:users,id',
            'check_out_time' => 'nullable|date',
            'check_in_time' => 'nullable|date|after_or_equal:check_out_time',
        ]);

        try {
            $b = ResourceBooking::create($data);
            return response()->json($b, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create booking'], 400);
        }
    }

    public function show(ResourceBooking $resource_booking)
    {
        return response()->json($resource_booking->load(['resource', 'group', 'checkedOutBy']));
    }

    public function update(Request $request, ResourceBooking $resource_booking)
    {
        $data = $request->validate([
            'check_out_time' => 'nullable|date',
            'check_in_time' => 'nullable|date|after_or_equal:check_out_time',
        ]);

        try {
            $resource_booking->update($data);
            return response()->json($resource_booking);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update booking'], 400);
        }
    }

    public function destroy(ResourceBooking $resource_booking)
    {
        try {
            $resource_booking->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete booking'], 400);
        }
    }
}
