<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json(Report::with(['caseFile','user'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'case_id' => 'required|exists:cases,case_id',
            'search_group_id' => 'nullable|exists:search_groups,group_id',
            'user_id' => 'required|exists:users,id',
            'report_type' => 'required|string|in:evidence,sighting,general',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'sighted_person' => 'nullable|string|max:255',
            'reported_at' => 'nullable|date',
            'status' => 'nullable|string|in:pending,verified,ressponded,falsed,dismissed',
        ]);
        try {
            $r = Report::create($data);
            return response()->json($r, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create report'], 400);
        }
    }

    public function show(Report $report)
    {
        $report->load(['caseFile','user','media']);
        if (Auth::check() && Auth::user()->role === 'officer') {
            return view('officers.viewReportDetails', ['report' => $report]);
        }
        if (Auth::check() && Auth::user()->role === 'group_leader') {
            return view('leaders.viewReportDetails', ['report' => $report]);
        }
        return response()->json($report);
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'report_type' => 'sometimes|string|in:evidence,sighting,general',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'sighted_person' => 'nullable|string|max:255',
            'reported_at' => 'nullable|date',
            'search_group_id' => 'nullable|exists:search_groups,group_id',
            'status' => 'nullable|string|in:pending,verified,ressponded,falsed,dismissed',
        ]);
        try {
            // authorize allowed transitions
            if (isset($data['status'])) {
                $current = $report->status;
                $target = $data['status'];
                $allowed = false;
                if ($current === $target) {
                    $allowed = true;
                } elseif ($current === 'pending' && in_array($target, ['verified','falsed'])) {
                    $allowed = true;
                } elseif (in_array($current, ['verified','falsed']) && in_array($target, ['ressponded','dismissed'])) {
                    $allowed = true;
                }
                if (!$allowed) {
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'Invalid status transition'], 400);
                    }
                    return redirect()->back()->with('error', 'Invalid status transition');
                }
            }

            $report->update($data);
            if ($request->expectsJson()) {
                return response()->json($report);
            }
            return redirect()->route('reports.show', $report->report_id)->with('success', 'Report updated successfully');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to update report'], 400);
            }
            return redirect()->back()->with('error', 'Failed to update report');
        }
    }

    public function destroy(Report $report)
    {
        try {
            $report->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete report'], 400);
        }
    }

    public function showAddReportForm($search_group)
    {
        $group = \App\Models\SearchGroup::with(['caseFile','volunteers.user'])->findOrFail($search_group);
        $case = $group->caseFile;
        $groupMembers = $group->volunteers->map(fn($v) => $v->user)->filter();
        return view('leaders.addReport', compact('group','case','groupMembers'));
    }

    public function addReport(Request $request, $search_group)
    {
        $group = \App\Models\SearchGroup::findOrFail($search_group);
        $caseId = $group->case_id;

        $data = $request->validate([
            'report_type' => 'required|string|in:evidence,sighting,general',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric|between:-90,90',
            'location_lng' => 'nullable|numeric|between:-180,180',
            'sighted_person' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'images' => 'sometimes',
            'images.*' => 'image|max:5120',
            'image_descriptions' => 'array',
            'image_descriptions.*' => 'nullable|string',
        ]);

        $payload = array_merge([
            'case_id' => $caseId,
            'search_group_id' => $group->group_id,
            'reported_at' => now(),
            'status' => 'pending',
        ], $data);

        $report = Report::create($payload);

        // Handle local image uploads to media_reports
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if ($files instanceof \Illuminate\Http\UploadedFile) {
                $files = [$files];
            } elseif (!is_array($files)) {
                $files = [];
            }
            $descs = $request->input('image_descriptions', []);
            foreach ($files as $i => $file) {
                if (!$file || !$file->isValid()) continue;
                $path = $file->store('report-images/'.$report->report_id, 'public');
                $url = \Illuminate\Support\Facades\Storage::url($path);
                \App\Models\MediaReport::create([
                    'report_id' => $report->report_id,
                    'uploaded_by' => $data['user_id'],
                    'url' => $url,
                    'description' => $descs[$i] ?? null,
                ]);
            }
        }

        return redirect()->route('search_groups.show', $group->group_id)->with('success', 'Report filed successfully');
    }
}
