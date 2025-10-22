<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, CaseFileController, SearchGroupController, GroupMemberController, ReportController, OfficerController, VolunteerController, SpecialVolunteerController, AlertController, ImageController, MediaReportController, NotificationController, officerDashboardController, MediaCaseController};

Route::get('/', function () {
    return view('welcome');
});

// Resource routes with names
Route::resource('users', UserController::class)->names('users');
Route::resource('cases', CaseFileController::class)->parameters(['cases' => 'case'])->names('cases');
Route::resource('search-groups', SearchGroupController::class)->parameters(['search-groups' => 'search_group'])->names('search_groups');
// Nested resource for managing group members of a search group
Route::resource('search-groups.members', GroupMemberController::class)
    ->shallow()
    ->only(['index','store','show','destroy'])
    ->parameters(['search-groups' => 'search_group', 'members' => 'volunteer'])
    ->names('search_groups.members');
Route::resource('reports', ReportController::class)->names('reports');
Route::resource('officers', OfficerController::class)->names('officers');
Route::resource('volunteers', VolunteerController::class)->names('volunteers');
Route::resource('special-volunteers', SpecialVolunteerController::class)->parameters(['special-volunteers' => 'special_volunteer'])->names('special_volunteers');
// Alert resource is registered after custom routes to avoid parameter route conflicts
Route::resource('media-reports', MediaReportController::class)->parameters(['media-reports' => 'media_report'])->names('media_reports');

// Case media (images attached directly to cases)
Route::post('/cases/{case}/media', [MediaCaseController::class, 'store'])->name('cases.media.store');
Route::delete('/media-cases/{mediaCase}', [MediaCaseController::class, 'destroy'])->name('media_cases.destroy');
// ResourceItem and ResourceBooking routes removed — these models/controllers were deprecated.
// If you plan to delete their files, it's now safe to remove them completely from the project.
Route::resource('notifications', NotificationController::class)->names('notifications');

// auth routes
// for auth redirect to find correct login route route (had to rename it to login)
Route::get('/loginform', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');  
Route::get('/signupform', [UserController::class, 'showSignupForm'])->name('signupform');
Route::post('/signup', [UserController::class, 'store'])->name('signup');


// dashboard routes (protected by auth + role)
Route::middleware(['auth', 'role:officer'])->group(function () {
    Route::get('/dashboard/officer', [officerDashboardController::class, 'showOfficerDashboard'])->name('dashboard.officer');
});
Route::middleware(['auth', 'role:volunteer'])->group(function () {
    Route::get('/dashboard/volunteer', [VolunteerController::class, 'showVolunteerDashboard'])->name('dashboard.volunteer');
});
Route::middleware(['auth', 'role:group_leader'])->group(function () {
    Route::get('/dashboard/groupleader', [SearchGroupController::class, 'showLeaderDashboard'])->name('dashboard.groupleader');
});
Route::middleware(['auth', 'role:citizen'])->group(function () {
    Route::get('/dashboard/citizen', [UserController::class, 'showCitizenDashboard'])->name('dashboard.citizen');
});
Route::middleware(['auth'])->get('/dashboard', [UserController::class, 'routeToDashboard'])->name('dashboardRouting');


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
Route::post('/search-groups/{search_group}/add-volunteer', [GroupMemberController::class, 'store'])
    ->name('search-groups.members.add');
Route::delete('/search-groups/{search_group}/members/remove/{volunteer_id}',
    [GroupMemberController::class, 'remove']
)->name('search_groups.members.remove');
Route::put('/search-groups/{search_group}', [SearchGroupController::class, 'startSearch'])
    ->name('searchGroup.start');
Route::put('/search-groups/{search_group}/end', [SearchGroupController::class, 'endSearch'])
    ->name('searchGroup.end');



// Report routes (leader context)
Route::get('/search-group/{search_group}/reports/create-form', [ReportController::class, 'showAddReportForm'])->name('reports.showCreateForm');
Route::post('/search-group/{search_group}/reports/submit', [ReportController::class, 'addReport'])->name('reports.add');


// Alert routes
Route::get('/alerts/create/case/{case}', [AlertController::class, 'showCreateFormForCase'])->name('alerts.create.case');
Route::get('/alerts/nearby', [AlertController::class, 'nearbyAlerts'])->name('alerts.nearby');
Route::resource('alerts', AlertController::class)->names('alerts');

// Contact page
Route::get('/contact', function(){ return view('contact'); })->name('contact');


// image upload test route
Route::get('/uptest', function () {
    return view('imgup');
});
