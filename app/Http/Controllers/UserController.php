<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'password' => 'required|string|min:4',
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
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            return redirect()->route('loginform')->with('success', 'Account created successfully!');
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
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $user->update($data);

            // if user is logged in, redirect him to profile page after update
            if (Auth::check() && Auth::id() === $user->id) {
                return redirect()->route('profile.page')->with('success', 'Profile updated successfully!');
            }
            else {return response()->json($user);}
            
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

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login (Request $request) {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember_me');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->role == 'officer') {
                return redirect()->route('dashboard.officer');
            }
            elseif ($user->role == 'volunteer') {
                return redirect()->route('dashboard.volunteer');
            }
            elseif ($user->role == 'group_leader') {
                return redirect()->route('dashboard.leader');
            }
            elseif ($user->role == 'specialVolunteer') {
                return redirect()->route('dashboard.specialVolunteer');
            }
            elseif ($user->role == 'citizen') {
                return redirect()->route('dashboard.citizen');
            }
        }
        else {
            return redirect()->route('loginform')->withErrors(['Invalid credentials']);
        }
    }

    public function logout (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/loginform');
    }

    public function showSignupForm()
    {
        return view('auth.signup');
    }

    public function showProfile()
    {
        return view('Profile.profilePage')->with('user', Auth::user());
    }

    public function showEditProfile()
    {
        return view('Profile.editProfilePage')->with('user', Auth::user());
    }


}
