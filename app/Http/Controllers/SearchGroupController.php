<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\SearchGroup;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchGroupController extends Controller
{
    // Show the create page for a case-specific search group
    public function showCreatePage($case_id)
    {
        $case = \App\Models\CaseFile::findOrFail($case_id);
        $selectedLeaderId = session('selected_leader_id');
        return view('officers.addSearchGroup', compact('case', 'selectedLeaderId'));
    }

    // Show the leader picker for a given case
    public function showChooseLeaderPage($case_id)
    {
        $case = \App\Models\CaseFile::findOrFail($case_id);

        session(['current_case_id' => $case_id]);

        $candidateLeaders = \App\Models\Volunteer::with('user')->get();

        $candidateOfficers = \App\Models\User::where('role', 'officer')->get();

        return view('officers.chooseLeader', compact('case','candidateLeaders','candidateOfficers'));
    }

    // Assign a leader and return to the add search group page preserving case id
    public function assignLeader(Request $request, $leader_id)
    {
        \App\Models\User::findOrFail($leader_id);

        $caseId = $request->input('case_id') ?? session('current_case_id');
        if (!$caseId) {
            return redirect()->back()->withErrors(['error' => 'Missing case context for leader assignment']);
        }

        session(['selected_leader_id' => $leader_id, 'current_case_id' => $caseId]);

    

        return redirect()->route('search-groups.create', ['case_id' => $caseId])
            ->with('selected_leader_id', $leader_id)
            ->with('success', 'Leader assigned');
    }
    public function index()
    {
        return response()->json(SearchGroup::with(['caseFile','leader'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'case_id' => 'required|exists:cases,case_id',
            'leader_id' => 'required|exists:users,id',
            'type' => 'required|string|in:citizen,terrainSpecial',
            'intensity' => 'nullable|string|in:basic,rigorous,extreme',
            'status' => 'nullable|string|in:active,paused,completed,time_assigned,time_unassigned',
            'start_time' => 'nullable|date',
            'duration' => 'nullable|integer|min:0',
            'report_back_time' => 'nullable|date',
            'max_volunteers' => 'nullable|integer|min:1',
            'available_volunteer_slots' => 'nullable|integer|min:0',
            'instruction' => 'nullable|string',
            'allocated_lat' => 'nullable|numeric|between:-90,90',
            'allocated_lng' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:0',
        ]);
        try {
            $sg = SearchGroup::create($data);
            return redirect()->route('dashboard.officer');
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create group'], 400);
        }
    }

    public function show(SearchGroup $search_group)
    {
        $search_group->load(['caseFile','leader','volunteers.user','reports.user']);
        if (Auth::check() && Auth::user()->role === 'officer') {
            return view('officers.viewSearchGroup', ['group' => $search_group]);
        }
        elseif (Auth::check() && Auth::user()->role === 'volunteer') {
            $case = $search_group->caseFile;
            return view('volunteers.viewSearchGroup', ['group' => $search_group, 'case' => $case]);
        }
        elseif (Auth::check() && Auth::user()->role === 'group_leader') {
            $case = $search_group->caseFile;
            $groupMembers = $search_group->volunteers->map(fn($v) => $v->user)->filter();
            return view('leaders.viewSearchGroup', ['group' => $search_group, 'case' => $case, 'groupMembers' => $groupMembers]);
        }
        return response()->json($search_group);
    }

    // Resource edit route: show edit form
    public function edit(SearchGroup $search_group)
    {
        if (Auth::check() && Auth::user()->role === 'officer') {
            return view('officers.editSearchGroup', ['group' => $search_group]);
        }
        abort(403);
    }

    public function update(Request $request, SearchGroup $search_group)
    {
        $data = $request->validate([
            'type' => 'sometimes|string|in:citizen,terrainSpecial',
            'intensity' => 'nullable|string|in:basic,rigorous,extreme',
            'status' => 'nullable|string|in:active,paused,completed,time_assigned,time_unassigned',
            'start_time' => 'nullable|date',
            'duration' => 'nullable|integer|min:0',
            'report_back_time' => 'nullable|date',
            'max_volunteers' => 'nullable|integer|min:1',
            'available_volunteer_slots' => 'nullable|integer|min:0',
            'instruction' => 'nullable|string',
            'allocated_lat' => 'nullable|numeric|between:-90,90',
            'allocated_lng' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:0',
        ]);
        try {
            $search_group->update($data);
            if ($request->expectsJson()) {
                return response()->json($search_group);
            }
            return redirect()->route('search-groups.show', $search_group->group_id)
                ->with('success', 'Search group updated successfully!');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to update group'], 400);
            }
            return redirect()->back()->with('error', 'Failed to update group')->withInput();
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

    // Optional friendly route similar to cases: show-edit-page
    public function showEditPage(SearchGroup $search_group)
    {
        return $this->edit($search_group);
    }

    // Show the leader's dashboard with their assigned search groups
    public function showLeaderDashboard()
    {
        $assignedSearchGroups = SearchGroup::where('leader_id', Auth::user()->id)
            ->with('caseFile')
            ->get();

        $activeSearchGroups = $assignedSearchGroups->where('status', 'active');
        return view('leaders.dashboard', compact('assignedSearchGroups', 'activeSearchGroups'));
    }

    // Start the search for a specific search group
    public function startSearch(Request $request, SearchGroup $search_group)
    {
        try {   
            $search_group->status = 'active';
            $search_group->save();

            return redirect()->back()
                ->with('success', 'Search started successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to start search')->withInput();
        }
    }

    // End the search for a specific search group
    public function endSearch(Request $request, SearchGroup $search_group)
    {
        try {   
            $search_group->status = 'time_unassigned';
            $search_group->report_back_time = now();
            $search_group->save();
            
            return redirect()->back()
                ->with('success', 'Search ended successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to end search')->withInput();
        }
    }

}
