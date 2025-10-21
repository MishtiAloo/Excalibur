<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\SpecialVolunteer;
use App\Models\Volunteer;
use App\Models\Alert;
use Illuminate\Http\Request;

class officerDashboardController extends Controller
{
    public function showOfficerDashboard () {
        $pendingVolunteers = Volunteer::where('vetting_status', 'pending')->get();
        $pendingSpecialVolunteers = SpecialVolunteer::where('vetting_status', 'pending')->get();
    $activeCases = CaseFile::where('status', 'active')->get();
    $activeAlerts = Alert::where('status', 'active')->orderBy('expires_at')->get();
    return view('officers.dashboard', compact('pendingVolunteers', 'pendingSpecialVolunteers', 'activeCases', 'activeAlerts'));
    }

    public function showCreatePage() {
        return view('officers.addCase');
    }

    public function showEditPage(CaseFile $case) {
        return view('officers.editCase', compact('case'));
    }
}
