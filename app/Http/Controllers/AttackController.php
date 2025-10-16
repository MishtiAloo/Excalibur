<?php

namespace App\Http\Controllers;

use App\Models\Attack;
use Illuminate\Http\Request;

class AttackController extends Controller
{
    public function index(){ return response()->json(Attack::with('report')->paginate(10)); }
    public function store(Request $request){
        $data = $request->validate(['report_id'=>'required|exists:reports,report_id|unique:attacks,report_id','attack_type'=>'required|string','victims_count'=>'nullable|integer','attacker'=>'nullable|string']);
        try { $a = Attack::create($data); return response()->json($a,201);} catch (\Throwable $e) { return response()->json(['error'=>'Failed to create attack'],400);} }
    public function show(Attack $attack){ return response()->json($attack->load('report')); }
    public function update(Request $request, Attack $attack){
        $data = $request->validate(['attack_type'=>'sometimes|string','victims_count'=>'nullable|integer','attacker'=>'nullable|string']);
        try { $attack->update($data); return response()->json($attack);} catch (\Throwable $e) { return response()->json(['error'=>'Failed to update attack'],400);} }
    public function destroy(Attack $attack){ try { $attack->delete(); return response()->json(null,204);} catch (\Throwable $e) { return response()->json(['error'=>'Failed to delete attack'],400);} }
}
