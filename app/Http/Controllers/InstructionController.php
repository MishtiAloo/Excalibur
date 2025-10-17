<?php

namespace App\Http\Controllers;

use App\Models\Instruction;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    public function index()
    {
        return response()->json(Instruction::with(['group', 'caseFile', 'officer'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group_id' => 'required|exists:search_groups,group_id',
            'case_id' => 'required|exists:cases,case_id',
            'officer_id' => 'required|exists:officers,officer_id',
            'details' => 'required|string',
            'issued_at' => 'nullable|date',
        ]);

        try {
            $i = Instruction::create($data);
            return response()->json($i, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create instruction'], 400);
        }
    }

    public function show(Instruction $instruction)
    {
        return response()->json($instruction->load(['group', 'caseFile', 'officer']));
    }

    public function update(Request $request, Instruction $instruction)
    {
        $data = $request->validate([
            'details' => 'sometimes|string',
            'issued_at' => 'nullable|date',
        ]);

        try {
            $instruction->update($data);
            return response()->json($instruction);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update instruction'], 400);
        }
    }

    public function destroy(Instruction $instruction)
    {
        try {
            $instruction->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete instruction'], 400);
        }
    }
}
