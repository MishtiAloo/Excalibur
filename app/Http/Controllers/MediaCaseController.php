<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\MediaCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaCaseController extends Controller
{
    public function store(Request $request, CaseFile $case)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|max:5120',
            'image_descriptions' => 'array',
            'image_descriptions.*' => 'nullable|string',
        ]);

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

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Images uploaded'], 201);
        }
        return back()->with('success', 'Case images uploaded');
    }

    public function destroy(MediaCase $mediaCase)
    {
        try {
            // Optionally delete file from storage if under /storage
            $path = str_replace('/storage/', '', $mediaCase->url);
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $mediaCase->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete media'], 400);
        }
    }
}
