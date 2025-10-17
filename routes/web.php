<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, CaseFileController, SearchGroupController, ReportController, OfficerController, VolunteerController, SpecialVolunteerController, SkillController, InstructionController, AlertController, MediaReportController, ResourceItemController, ResourceBookingController, NotificationController,TipController, EvidenceController, SightingController, HazardController, AttackController, officerDashboardController};

Route::get('/', function () {
    return view('welcome');
});

// Resource routes with names
Route::resource('users', UserController::class)->names('users');
Route::resource('cases', CaseFileController::class)->parameters(['cases' => 'case'])->names('cases');
Route::resource('search-groups', SearchGroupController::class)->parameters(['search-groups' => 'search_group'])->names('search_groups');
Route::resource('reports', ReportController::class)->names('reports');
Route::resource('tips', TipController::class)->names('tips');
Route::resource('evidences', EvidenceController::class)->names('evidences');
Route::resource('sightings', SightingController::class)->names('sightings');
Route::resource('hazards', HazardController::class)->names('hazards');
Route::resource('attacks', AttackController::class)->names('attacks');
Route::resource('officers', OfficerController::class)->names('officers');
Route::resource('volunteers', VolunteerController::class)->names('volunteers');
Route::resource('special-volunteers', SpecialVolunteerController::class)->parameters(['special-volunteers' => 'special_volunteer'])->names('special_volunteers');
Route::resource('skills', SkillController::class)->names('skills');
Route::resource('instructions', InstructionController::class)->names('instructions');
Route::resource('alerts', AlertController::class)->names('alerts');
Route::resource('media-reports', MediaReportController::class)->parameters(['media-reports' => 'media_report'])->names('media_reports');
Route::resource('resources', ResourceItemController::class)->names('resources');
Route::resource('resource-bookings', ResourceBookingController::class)->parameters(['resource-bookings' => 'resource_booking'])->names('resource_bookings');
Route::resource('notifications', NotificationController::class)->names('notifications');

// auth routes
// for auth redirect to find correct login route route (had to rename it to login)
Route::get('/loginform', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');  
Route::get('/signupform', [UserController::class, 'showSignupForm'])->name('signupform');
Route::post('/signup', [UserController::class, 'store'])->name('signup');


// dashboard routes
Route::get('/dashboard/officer', [officerDashboardController::class, 'onPageLoad'])->name('dashboard.officer');


// profile route
Route::middleware('auth')->group(function () {
    Route::get('/profilepage', [UserController::class, 'showProfile'])->name('profile.page');
});
Route::get('/editprofilepage', [UserController::class, 'showEditProfile'])->name('profile.edit');
