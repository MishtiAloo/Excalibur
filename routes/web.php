<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, CaseFileController, SearchGroupController, ReportController, OfficerController, VolunteerController, SpecialVolunteerController, SkillController, AlertController, MediaReportController, ResourceItemController, ResourceBookingController, NotificationController, officerDashboardController};

Route::get('/', function () {
    return view('welcome');
});

// Resource routes with names
Route::resource('users', UserController::class)->names('users');
Route::resource('cases', CaseFileController::class)->parameters(['cases' => 'case'])->names('cases');
Route::resource('search-groups', SearchGroupController::class)->parameters(['search-groups' => 'search_group'])->names('search_groups');
Route::resource('reports', ReportController::class)->names('reports');
Route::resource('officers', OfficerController::class)->names('officers');
Route::resource('volunteers', VolunteerController::class)->names('volunteers');
Route::resource('special-volunteers', SpecialVolunteerController::class)->parameters(['special-volunteers' => 'special_volunteer'])->names('special_volunteers');
Route::resource('skills', SkillController::class)->names('skills');
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
Route::get('/dashboard/officer', [officerDashboardController::class, 'showOfficerDashboard'])->name('dashboard.officer');
Route::get('/dashboard/volunteer', [VolunteerController::class, 'showVolunteerDashboard'])->name('dashboard.volunteer');
Route::get('/dashboard/groupleader', [SearchGroupController::class, 'showLeaderDashboard'])->name('dashboard.groupleader');
Route::get('/dashboard/citizen', [UserController::class, 'showCitizenDashboard'])->name('dashboard.citizen');
Route::get('/dashboard', [UserController::class, 'routeToDashboard'])->name('dashboardRouting');


// profile route
Route::middleware('auth')->group(function () {
    Route::get('/profilepage', [UserController::class, 'showProfile'])->name('profile.page');
    Route::get('/editprofilepage', [UserController::class, 'showEditProfile'])->name('profile.edit');
});

// apply routes
Route::post('/apply/volunteer', [VolunteerController::class, 'applyVolunteer'])->name('volunteer.apply');
Route::post('/apply/special-volunteer', [SpecialVolunteerController::class, 'applySpecialVolunteer'])->name('specialvolunteer.apply');

// case routes
Route::get('/dashboard/officer/show-create-page', [officerDashboardController::class, 'showCreatePage'])->name('cases.showCreatePage');
Route::get('/cases/show-edit-page/{case}', [officerDashboardController::class, 'showEditPage'])->name('cases.showEditPage');

// SEARCH GROUP RELATED ROUTES
Route::get('/search-groups/show-create-page/{case_id}', [SearchGroupController::class, 'showCreatePage'])->name('search-groups.create');
Route::get('/search-groups/choose-leader/{case_id}', [SearchGroupController::class, 'showChooseLeaderPage'])
    ->name('chooseLeader');
Route::post('/search-groups/assign-leader/{leader_id}', [SearchGroupController::class, 'assignLeader'])
    ->name('search-groups.assignLeader');   
Route::get('/search-groups/show-edit-page/{search_group}', [SearchGroupController::class, 'showEditPage'])->name('search-groups.showEditPage');
