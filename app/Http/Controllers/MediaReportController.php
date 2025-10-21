<?php

namespace App\Http\Controllers;

use App\Models\MediaReport;
use Illuminate\Http\Request;

class MediaReportController extends Controller
{
    public function index()
    {
        return response()->json(MediaReport::with(['report', 'uploader'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_id' => 'required|exists:reports,report_id',
            'uploaded_by' => 'required|exists:users,id',
            'url' => 'required|url',
            'description' => 'nullable|string',
        ]);

        try {
            $m = MediaReport::create($data);
            return response()->json($m, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create media'], 400);
        }
    }

    public function show(MediaReport $media_report)
    {
        return response()->json($media_report->load(['report', 'uploader']));
    }

    public function update(Request $request, MediaReport $media_report)
    {
        $data = $request->validate([
            'url' => 'sometimes|url',
            'description' => 'nullable|string',
        ]);

        try {
            $media_report->update($data);
            return response()->json($media_report);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update media'], 400);
        }
    }

    public function destroy(MediaReport $media_report)
    {
        try {
            $media_report->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete media'], 400);
        }
    }
}
