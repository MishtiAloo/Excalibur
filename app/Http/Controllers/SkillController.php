<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(){ return response()->json(Skill::paginate(10)); }
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|in:navigation,drone,boat,mountain,forest,medic,diver,canine_handler,leadership',
            'description' => 'nullable|string',
        ]);

        try {
            $s = Skill::create($data);
            return response()->json($s, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create skill'], 400);
        }
    }
    public function show(Skill $skill){ return response()->json($skill); }
    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'type' => 'sometimes|string|in:navigation,drone,boat,mountain,forest,medic,diver,canine_handler,leadership',
            'description' => 'nullable|string',
        ]);

        try {
            $skill->update($data);
            return response()->json($skill);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update skill'], 400);
        }
    }
    public function destroy(Skill $skill){ try { $skill->delete(); return response()->json(null,204);} catch (\Throwable $e) {return response()->json(['error'=>'Failed to delete skill'],400);} }
}
