<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        return response()->json(Alert::with(['caseFile', 'approvedByOfficer'])->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'case_id' => 'required|exists:cases,case_id',
            'alert_type' => 'required|string|in:amber,silver,red,yellow',
            'status' => 'nullable|string|in:active,expired,cancelled',
            'approved_by' => 'nullable|exists:officers,officer_id',
            'expires_at' => 'nullable|date',
            'message' => 'nullable|string',
        ]);

        try {
            $a = Alert::create($data);
            return redirect()->route('dashboard.officer')->with('success', 'Alert created successfully!');
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.officer')->with('error', 'Failed to create alert')->withInput();
        }
    }

    public function show(Alert $alert)
    {
        return response()->json($alert->load(['caseFile', 'approvedByOfficer']));
    }

    public function update(Request $request, Alert $alert)
    {
        $data = $request->validate([
            'alert_type' => 'sometimes|string|in:amber,silver,red,yellow',
            'status' => 'nullable|string|in:active,expired,cancelled',
            'approved_by' => 'nullable|exists:officers,officer_id',
            'expires_at' => 'nullable|date',
            'message' => 'nullable|string',
        ]);

        try {
            $alert->update($data);
            return redirect()->route('dashboard.officer')->with('success', 'Alert updated successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to update alert')->withInput();
        }
    }

    public function destroy(Alert $alert)
    {
        try {
            $alert->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete alert'], 400);
        }
    }

    // Show create form for a specific case (officer view)
    public function showCreateFormForCase($case)
    {
        $caseModel = \App\Models\CaseFile::findOrFail($case);
        return view('officers.addAlert', ['case' => $caseModel]);
    }

    // List nearby alerts for the authenticated user
    public function nearbyAlerts()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $alerts = collect();
        if ($user && $user->permanent_lat && $user->permanent_lng) {
            $lat = $user->permanent_lat;
            $lng = $user->permanent_lng;
            $radiusKm = 100;
            $alerts = Alert::where('alerts.status', 'active')
                ->join('cases', 'cases.case_id', '=', 'alerts.case_id')
                ->select('alerts.*')
                ->selectRaw(sprintf('(( 6371 * acos( cos( radians(%1$s) ) * cos( radians( cases.coverage_lat ) ) * cos( radians( cases.coverage_lng ) - radians(%2$s) ) + sin( radians(%1$s) ) * sin( radians( cases.coverage_lat ) ) ) )) as distance_km', $lat, $lng))
                ->havingRaw('distance_km <= ?', [$radiusKm])
                ->orderBy('distance_km')
                ->get();
        }
        // send case also
        $case_ids = $alerts->pluck('case_id')->unique();
        $cases = \App\Models\CaseFile::whereIn('case_id', $case_ids)->get();

        return view('alerts', ['alerts' => $alerts, 'cases' => $cases]);
    }
}
