<?php

namespace App\Http\Controllers;

use App\Models\Hazard;
use Illuminate\Http\Request;

class HazardController extends Controller
{
    public function index(){ return response()->json(Hazard::with('report')->paginate(10)); }
    public function store(Request $request){
        $data = $request->validate(['report_id'=>'required|exists:reports,report_id|unique:hazards,report_id','hazard_type'=>'required|string','severity'=>'nullable|string']);
        try { $h = Hazard::create($data); return response()->json($h,201);} catch (\Throwable $e) { return response()->json(['error'=>'Failed to create hazard'],400);} }
    public function show(Hazard $hazard){ return response()->json($hazard->load('report')); }
    public function update(Request $request, Hazard $hazard){
        $data = $request->validate(['hazard_type'=>'sometimes|string','severity'=>'nullable|string']);
        try { $hazard->update($data); return response()->json($hazard);} catch (\Throwable $e) { return response()->json(['error'=>'Failed to update hazard'],400);} }
    public function destroy(Hazard $hazard){ try { $hazard->delete(); return response()->json(null,204);} catch (\Throwable $e) { return response()->json(['error'=>'Failed to delete hazard'],400);} }
}
