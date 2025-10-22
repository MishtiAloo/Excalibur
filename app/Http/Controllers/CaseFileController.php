<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MediaCase;
use Illuminate\Support\Facades\Auth;

class CaseFileController extends Controller
{
    public function index()
    {
        return response()->json(CaseFile::with('creator')->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'created_by' => 'required|exists:users,id',
            'case_type' => 'required|string|in:missing',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coverage_lat' => 'nullable|numeric|between:-90,90',
            'coverage_lng' => 'nullable|numeric|between:-180,180',
            'coverage_radius' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,under_investigation,resolved,closed',
            'urgency' => 'nullable|string|in:low,medium,high,critical,national',
        ]);
        try {
            $case = CaseFile::create($data);

            // Handle case images via MediaCase, if any
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
                    $path = $file->store('case-images/'.$case->case_id, 'public');
                    $url = Storage::url($path);
                    MediaCase::create([
                        'case_id' => $case->case_id,
                        'uploaded_by' => Auth::id(),
                        'url' => $url,
                        'description' => $descs[$i] ?? null,
                    ]);
                }
            }

            return redirect()->route('dashboard.officer')->with('success', 'Case created successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to create case')->withInput();
        }
    }

    public function show(CaseFile $case)
    {
        $case->load(['creator','searchGroups']);

        if (Auth::check() && Auth::user()->role === 'officer') {
            return view('officers.viewCaseDetails', ['case' => $case]);
        } elseif (Auth::check() && Auth::user()->role === 'volunteer') {
             return view('officers.viewCaseDetails', ['case' => $case]);
        }

    }

    public function update(Request $request, CaseFile $case)
    {
        $data = $request->validate([
            'case_type' => 'sometimes|string|in:missing',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'coverage_lat' => 'nullable|numeric|between:-90,90',
            'coverage_lng' => 'nullable|numeric|between:-180,180',
            'coverage_radius' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,under_investigation,resolved,closed',
            'urgency' => 'nullable|string|in:low,medium,high,critical,national',
        ]);
        try {
            $case->update($data);
            return redirect()->route('dashboard.officer')->with('success', 'Case updated successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to update case')->withInput();
        }
    }

    public function destroy(CaseFile $case)
    {
        try {
            $case->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete case'], 400);
        }
    }

}
