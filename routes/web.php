<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, CaseFileController, SearchGroupController, ReportController, OfficerController, VolunteerController, SpecialVolunteerController, SkillController, InstructionController, AlertController, MediaReportController, ResourceItemController, ResourceBookingController, NotificationController};
use App\Http\Controllers\{TipController, EvidenceController, SightingController, HazardController, AttackController};

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
