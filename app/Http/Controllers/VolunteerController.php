<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\SearchGroup;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    public function index()
    {
        return response()->json(Volunteer::with(['user', 'specialVolunteer'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'volunteer_id' => 'required|exists:users,id|unique:volunteers,volunteer_id',
            'vetting_status' => 'nullable|string|in:pending,approved,rejected',
            'availability' => 'nullable|string|in:available,busy,inactive',
        ]);

        try {
            $v = Volunteer::create($data);
            return response()->json($v, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create volunteer'], 400);
        }
    }

    public function show(Volunteer $volunteer)
    {
        return response()->json($volunteer->load(['user', 'specialVolunteer', 'searchGroups']));
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $data = $request->validate([
            'vetting_status' => 'nullable|string|in:pending,approved,rejected',
            'availability' => 'nullable|string|in:available,busy,inactive',
        ]);

        // For verify buttons
        if ($request->action === 'approve') {
            $data['vetting_status'] = 'approved';

            if ($volunteer->user) {
                $volunteer->user->role = 'volunteer';
                $volunteer->user->save();
            }

        } elseif ($request->action === 'reject') {
            $data['vetting_status'] = 'rejected';
        }

        try {
            $volunteer->update($data);
            return response()->json($volunteer);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update volunteer'], 400);
        }
    }


    public function destroy(Volunteer $volunteer)
    {
        try {
            $volunteer->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete volunteer'], 400);
        }
    }

    //apply volunteer function to add volunteer id as user id, vetting status as pending and availability as available
    public function applyVolunteer(Request $request)
    {
        $data = $request->validate([
            'volunteer_id' => 'required|exists:users,id|unique:volunteers,volunteer_id',
        ]);
        try {
            $data['vetting_status'] = 'pending';
            $data['availability'] = 'available';
            $volunteer = Volunteer::create($data);
            return redirect()->back()->with('success', 'Volunteer application submitted successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to apply as Volunteer']);
        }
    }

    //showvolunteerdashboard
    public function showVolunteerDashboard()
    {
        $activeCases = CaseFile::where('status', 'active')->get();
        $assignedSearchGroups = SearchGroup::whereHas('volunteers', function ($query) {
            $query->where('volunteers.volunteer_id', Auth::user()->id);
        })
        ->with('caseFile')
        ->get();

        return view('volunteers.dashboard', compact('activeCases', 'assignedSearchGroups')); 
    }
}
