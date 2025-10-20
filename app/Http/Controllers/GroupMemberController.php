<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use App\Models\SearchGroup;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    // List members of a search group
    public function index($search_group)
    {
        $group = SearchGroup::with('volunteers.user')->findOrFail($search_group);
        return response()->json([
            'group_id' => $group->group_id,
            'members' => $group->volunteers,
        ]);
    }

    // Add a volunteer to the group
    public function store(Request $request, $search_group)
    {
        $request->validate([
            'volunteer_id' => 'required|exists:volunteers,volunteer_id',
        ]);

        $group = SearchGroup::findOrFail($search_group);
        $volunteerId = (int) $request->input('volunteer_id');

        if (!$group->volunteers()->where('volunteers.volunteer_id', $volunteerId)->exists()) {
            $group->volunteers()->attach($volunteerId);
        }

        Volunteer::where('volunteer_id', $volunteerId)
        ->update(['availability' => 'busy']);

        $group->decrement('available_volunteer_slots');

        return redirect()->back()->with('success', 'You have joined the group successfully!');
    }


    // Show one membership (by volunteer id)
    public function show($search_group, $volunteer_id)
    {
        $group = SearchGroup::findOrFail($search_group);
        $member = $group->volunteers()->where('volunteer_id', $volunteer_id)->first();
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }
        return response()->json($member);
    }

    // Remove a volunteer from the group
    public function destroy($search_group, $volunteer_id)
    {
        $group = SearchGroup::findOrFail($search_group);
        $group->volunteers()->detach($volunteer_id);
        $group->increment('available_volunteer_slots');
        return response()->json(null, 204);
    }

    // Remove a volunteer from the group
    public function remove($search_group, $volunteer_id)
    {
        $group = SearchGroup::findOrFail($search_group);
        $group->volunteers()->detach($volunteer_id);
        $group->increment('available_volunteer_slots');
        return response()->json(null, 204);
    }
}
