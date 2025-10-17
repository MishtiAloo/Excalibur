<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(Notification::with('user')->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|in:alert,update,new_search_start',
            'message' => 'required|string',
        ]);

        try {
            $n = Notification::create($data);
            return response()->json($n, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create notification'], 400);
        }
    }

    public function show(Notification $notification)
    {
        return response()->json($notification->load('user'));
    }

    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'type' => 'sometimes|string|in:alert,update,new_search_start',
            'message' => 'sometimes|string',
        ]);

        try {
            $notification->update($data);
            return response()->json($notification);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update notification'], 400);
        }
    }

    public function destroy(Notification $notification)
    {
        try {
            $notification->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete notification'], 400);
        }
    }
}
