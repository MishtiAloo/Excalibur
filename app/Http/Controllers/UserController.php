<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4', // do not hash per requirement
            'nid' => 'nullable|string|max:32',
            'phone' => 'nullable|string|max:32',
            'role' => 'nullable|string|in:citizen,officer,volunteer,specialVolunteer,watchDog,group_leader',
            'status' => 'nullable|string|in:active,suspended,inactive',
            'info_credibility' => 'nullable|integer|min:0|max:100',
            'responsiveness' => 'nullable|integer|min:0|max:100',
            'permanent_lat' => 'nullable|numeric|between:-90,90',
            'permanent_lng' => 'nullable|numeric|between:-180,180',
            'current_lat' => 'nullable|numeric|between:-90,90',
            'current_lng' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            $user = User::create($data);
            return response()->json($user, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create user'], 400);
        }
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)    
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:4',
            'nid' => 'nullable|string|max:32',
            'phone' => 'nullable|string|max:32',
            'role' => 'nullable|string|in:citizen,officer,volunteer,specialVolunteer,watchDog,group_leader',
            'status' => 'nullable|string|in:active,suspended,inactive',
            'info_credibility' => 'nullable|integer|min:0|max:100',
            'responsiveness' => 'nullable|integer|min:0|max:100',
            'permanent_lat' => 'nullable|numeric|between:-90,90',
            'permanent_lng' => 'nullable|numeric|between:-180,180',
            'current_lat' => 'nullable|numeric|between:-90,90',
            'current_lng' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            $user->update($data);
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update user'], 400);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete user'], 400);
        }
    }
}
