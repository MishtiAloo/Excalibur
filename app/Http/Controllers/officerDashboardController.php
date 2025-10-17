<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class officerDashboardController extends Controller
{
    public function onPageLoad () {
        return view('/officers.dashboard');
    }
}
