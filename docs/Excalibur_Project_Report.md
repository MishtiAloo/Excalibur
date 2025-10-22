# Excalibur Project Report

Date: 2025-10-22

## 1) Domain Model and Relationships

Entities (PK in parentheses) and key relationships:

-   User (id)

    -   Attributes: name, email, password, nid, phone, role, status, info_credibility, responsiveness, permanent_lat/lng, current_lat/lng
    -   Relations:
        -   hasOne Officer (officer_id = users.id)
        -   hasOne Volunteer (volunteer_id = users.id)
        -   hasMany CaseFile (created_by)
        -   hasMany SearchGroup as leader (leader_id)
        -   hasMany Report (user_id)
        -   hasMany Notification (user_id)

-   Officer (officer_id) [non-incrementing FK to users.id]

    -   Attributes: badge_no, department, rank
    -   Relations:
        -   belongsTo User (officer_id)
        -   hasMany SpecialVolunteer (verified_by_officer)
        -   hasMany Alert (approved_by)

-   Volunteer (volunteer_id) [non-incrementing FK to users.id]

    -   Attributes: vetting_status, availability
    -   Relations:
        -   belongsTo User (volunteer_id)
        -   hasOne SpecialVolunteer (special_volunteer_id)
        -   belongsToMany SearchGroup via group_members (volunteer_id ↔ group_id)

-   SpecialVolunteer (special_volunteer_id)

    -   Attributes: terrain_type, vetting_status, verified_by_officer
    -   Relations:
        -   belongsTo Volunteer (special_volunteer_id)
        -   belongsTo Officer (verified_by_officer)

-   CaseFile (case_id)

    -   Attributes: created_by, case_type, title, description, coverage_lat/lng, coverage_radius, status, urgency
    -   Relations:
        -   belongsTo User as creator (created_by)
        -   hasMany SearchGroup (case_id)
        -   hasMany Report (case_id)
        -   hasMany MediaCase (case_id)
        -   hasMany Alert (case_id)

-   SearchGroup (group_id)

    -   Attributes: case_id, leader_id, type, intensity, status, start_time, duration, report_back_time, max_volunteers, available_volunteer_slots, instruction, allocated_lat/lng, radius
    -   Relations:
        -   belongsTo CaseFile (case_id)
        -   belongsTo User as leader (leader_id)
        -   belongsToMany Volunteer via group_members (group_id ↔ volunteer_id)
        -   hasMany Report (search_group_id)

-   Report (report_id)

    -   Attributes: case_id, search_group_id, user_id, report_type, description, location_lat/lng, sighted_person, reported_at, status
    -   Relations:
        -   belongsTo CaseFile (case_id)
        -   belongsTo User (user_id)
        -   belongsTo SearchGroup (search_group_id)
        -   hasMany MediaReport (report_id)

-   MediaReport (media_id)

    -   Attributes: report_id, uploaded_by, url, description
    -   Relations:
        -   belongsTo Report (report_id)
        -   belongsTo User as uploader (uploaded_by)

-   MediaCase (media_id)

    -   Attributes: case_id, uploaded_by, url, description
    -   Relations:
        -   belongsTo CaseFile (case_id)
        -   belongsTo User as uploader (uploaded_by)

-   Alert (alert_id)

    -   Attributes: case_id, alert_type, status, approved_by, expires_at, message
    -   Relations:
        -   belongsTo CaseFile (case_id)
        -   belongsTo Officer (approved_by)

-   Notification (notification_id)

    -   Attributes: user_id, type, message
    -   Relations:
        -   belongsTo User (user_id)

-   GroupMember (pivot for volunteers in groups)
    -   Table: group_members (group_id, volunteer_id)
    -   Relations: belongsTo SearchGroup (group_id), belongsTo Volunteer (volunteer_id)

## 2) Features Overview

-   Authentication & Profiles

    -   Login, Signup, Logout (UserController)
    -   Profile view/edit (auth middleware guarded)

-   Dashboards

    -   Officer dashboard: pending volunteer/special-volunteer approvals, active cases, active alerts
    -   Group leader dashboard: assigned search groups, active groups
    -   Volunteer dashboard: volunteer-specific view
    -   Citizen dashboard: citizen-specific view
    -   Auto-route to appropriate dashboard based on role

-   Case Management (Officer)

    -   Create/edit case with location radius
    -   View case details with search groups, images (MediaCase), and navigation to edit/add alert
    -   Upload case images (during creation and on details page) with per-image descriptions

-   Search Group Management

    -   Create group for a case; choose/assign leader (User or Volunteer)
    -   Edit group data; start/end search lifecycle; manage volunteer members
    -   Group details view differs by role (officer/leader/volunteer)

-   Reporting (Leader)

    -   File reports for a search group (evidence/sighting/general)
    -   Optional geo-location and sighted person
    -   Attach multiple images to a report (MediaReport) with descriptions
    -   View report details (officer and leader views) including attached images

-   Alerts

    -   Create alert for a case (officer), status lifecycle (active/expired/cancelled)
    -   Nearby alerts page using Haversine filter from user’s permanent coords

-   Notifications
    -   Basic notification resource endpoints; seeded per user

## 3) Program Flows (Activity-style, arrows only)

Legend: View -> Controller -> Model/DB -> Redirect/View

### Authentication

-   Login form -> POST /login -> UserController@login -> (Auth attempt) -> if success -> redirect to /dashboard -> routeToDashboard -> appropriate dashboard view
-   Signup form -> POST /signup -> UserController@store -> create User -> redirect to login or dashboard
-   Logout click -> POST /logout -> UserController@logout -> invalidate session -> redirect '/'

### Dashboard Routing

-   GET /dashboard -> UserController@routeToDashboard -> role check ->
    -   officer -> officers.dashboard
    -   group_leader -> leaders.dashboard
    -   volunteer -> volunteers.dashboard
    -   citizen/others -> citizens.dashboard

### Case: Create

-   Button on officer dashboard -> GET /dashboard/officer/show-create-page -> officers.addCase -> submit form (multipart) -> POST /cases -> CaseFileController@store -> validate -> create CaseFile ->
    -> if images[] present -> store each file (public/case-images/{case_id}) -> create MediaCase rows ->
    -> redirect dashboard.officer (success)

### Case: View Details

-   Click case in dashboard -> GET /cases/{case} -> CaseFileController@show -> load creator, searchGroups -> view officers.viewCaseDetails ->
    -> show case info, groups, Case Images (MediaCase)
    -> optional upload form (Add Image(s)) -> POST /cases/{case}/media -> MediaCaseController@store -> store files -> create MediaCase -> back()

### Case: Edit

-   Link on case details -> GET /cases/show-edit-page/{case} -> officers.editCase -> submit -> PUT /cases/{case} -> CaseFileController@update -> update -> redirect dashboard.officer

### Search Group: Create

-   From case details -> GET /search-groups/show-create-page/{case_id} -> officers.addSearchGroup -> pick leader ->
    -> GET /search-groups/choose-leader/{case_id} -> SearchGroupController@showChooseLeaderPage -> choose ->
    -> POST /search-groups/assign-leader/{leader_id} (case_id in session) -> assignLeader -> session selected_leader_id -> redirect back to addSearchGroup ->
    -> POST /search-groups -> SearchGroupController@store -> create SearchGroup -> redirect dashboard.officer

### Search Group: Manage Volunteers

-   On group view -> add/remove volunteers ->
    -> POST /search-groups/{group}/add-volunteer -> GroupMemberController@store -> pivot insert -> back
    -> DELETE /search-groups/{group}/members/remove/{volunteer_id} -> GroupMemberController@remove -> pivot delete -> back

### Search Group: Lifecycle

-   Start -> PUT /search-groups/{group} (route name searchGroup.start) -> SearchGroupController@startSearch -> set status=active -> back
-   End -> PUT /search-groups/{group}/end -> SearchGroupController@endSearch -> status=time_unassigned, set report_back_time -> back

### Group View (by role)

-   GET /search-groups/{group} -> SearchGroupController@show -> load relations ->
    -   officer -> officers.viewSearchGroup
    -   group_leader -> leaders.viewSearchGroup (with case + members)
    -   volunteer -> volunteers.viewSearchGroup (with case)

### Report: Create (Leader)

-   From group page -> GET /search-group/{group}/reports/create-form -> ReportController@showAddReportForm -> leaders.addReport ->
    -> add data + Add Image(s) -> POST /search-group/{group}/reports/submit -> ReportController@addReport -> validate ->
    -> create Report (case_id from group) -> if images[] -> store file(s) to public/report-images/{report_id} -> create MediaReport rows -> redirect back to group page (success)

### Report: View Details

-   Officer -> GET /reports/{report} -> ReportController@show -> view officers.viewReportDetails -> details + attached images
-   Group Leader -> GET /reports/{report} -> ReportController@show -> view leaders.viewReportDetails -> details + attached images

### Report: Status Transitions (Officer)

-   From officer report details -> action buttons -> PUT /reports/{report} -> ReportController@update -> if valid transition
    -   pending -> verified | falsed
    -   verified | falsed -> ressponded | dismissed
        -> redirect back with success or error

### Alerts

-   Create for case -> GET /alerts/create/case/{case} -> AlertController@showCreateFormForCase -> officers.addAlert -> submit -> POST /alerts -> AlertController@store -> create Alert -> redirect officer dashboard
-   Nearby alerts -> GET /alerts/nearby -> AlertController@nearbyAlerts -> (Haversine: user permanent coords vs cases coverage_lat/lng) -> list alerts view (alerts.blade.php)
-   Alert CRUD endpoints via resource controller for JSON

### Notifications

-   Standard resource endpoints for JSON; seeded per user

## 4) Use Case Diagram (text arrows)

Actors: Officer, Group Leader, Volunteer, Citizen, User (any), Admin (implicit via officer)

-   User -> Login/Signup/Logout -> Authentication
-   User -> View Profile -> Profile
-   User -> Edit Profile -> Profile

-   Officer -> View Dashboard -> officers.dashboard
-   Officer -> Create Case -> CaseFileController@store
-   Officer -> Edit Case -> CaseFileController@update
-   Officer -> View Case Details -> CaseFileController@show
-   Officer -> Upload Case Images -> MediaCaseController@store
-   Officer -> Create Alert for Case -> AlertController@store
-   Officer -> Browse Nearby Alerts -> AlertController@nearbyAlerts
-   Officer -> Create Search Group -> SearchGroupController@store
-   Officer -> Choose/Assign Leader -> SearchGroupController@showChooseLeaderPage/assignLeader
-   Officer -> Manage Group Members -> GroupMemberController@store/remove
-   Officer -> Start/End Search -> SearchGroupController@startSearch/endSearch
-   Officer -> View Report Details -> ReportController@show (officer view)
-   Officer -> Transition Report Status -> ReportController@update

-   Group Leader -> View Leader Dashboard -> leaders.dashboard
-   Group Leader -> View Assigned Groups -> SearchGroupController@show
-   Group Leader -> File Report -> ReportController@addReport
-   Group Leader -> Attach Report Images -> ReportController@addReport (MediaReport)
-   Group Leader -> View Report Details -> ReportController@show (leader view)

-   Volunteer -> View Volunteer Dashboard -> volunteers.dashboard
-   Volunteer -> View Assigned Group -> SearchGroupController@show (volunteer view)
-   Volunteer -> Apply as Volunteer -> VolunteerController@applyVolunteer
-   Volunteer -> Apply as Special Volunteer -> SpecialVolunteerController@applySpecialVolunteer

-   Citizen -> View Citizen Dashboard -> citizens.dashboard
-   Citizen -> Browse Alerts -> alerts.blade.php (nearby route optional)

## 5) Key Data and Constraints

-   Primary keys: users.id, officers.officer_id (FK), volunteers.volunteer_id (FK), cases.case_id, search_groups.group_id, reports.report_id, alerts.alert_id, media_reports.media_id, media_cases.media_id, notifications.notification_id
-   Foreign keys:

    -   CaseFile.created_by → users.id
    -   SearchGroup.case_id → cases.case_id
    -   SearchGroup.leader_id → users.id
    -   Report.case_id → cases.case_id; Report.search_group_id → search_groups.group_id; Report.user_id → users.id
    -   MediaReport.report_id → reports.report_id; MediaReport.uploaded_by → users.id
    -   MediaCase.case_id → cases.case_id; MediaCase.uploaded_by → users.id
    -   Alert.case_id → cases.case_id; Alert.approved_by → officers.officer_id
    -   Notification.user_id → users.id
    -   group_members.group_id ↔ search_groups.group_id; group_members.volunteer_id ↔ volunteers.volunteer_id

-   Enumerations (validated):

    -   report_type: evidence | sighting | general
    -   report.status: pending | verified | ressponded | falsed | dismissed (note: "ressponded" spelling is in code)
    -   search_group.type: citizen | terrainSpecial
    -   search_group.intensity: basic | rigorous | extreme
    -   search_group.status: active | paused | completed | time_assigned | time_unassigned
    -   case.status: active | under_investigation | resolved | closed
    -   case.urgency: low | medium | high | critical | national
    -   alert.alert_type: amber | silver | red | yellow
    -   alert.status: active | expired | cancelled

-   Geospatial logic: Haversine computation for nearby alerts using user permanent_lat/lng vs case coverage_lat/lng with a 100km radius.

-   Media storage: public disk with storage:link;
    -   Case images: /storage/case-images/{case_id}/...
    -   Report images: /storage/report-images/{report_id}/...

## 6) Notable Views

-   Officer views: dashboard, addCase, editCase, viewCaseDetails (with case images), addAlert, addSearchGroup, chooseLeader, editSearchGroup, viewSearchGroup, viewReportDetails (with report images)
-   Leader views: dashboard, viewSearchGroup, addReport (with multi-image), viewReportDetails (with report images)
-   Volunteer views: dashboard, viewSearchGroup
-   Alerts: alerts.blade.php (nearby list)
-   Shared layout: resources/views/Layouts/layout.blade.php

## 7) Implementation Notes

-   Controllers are role-aware and route to role-specific views where applicable.
-   Order of routes matters for alerts: custom routes (e.g., /alerts/nearby) are defined before the resource to avoid being captured by /alerts/{id}.
-   Report status transitions are enforced in the controller (simple guard logic).
-   Group member management uses a pivot table with a bespoke controller.
-   ResourceItem/ResourceBooking were removed; related references are gone.

## 8) Quick Future Enhancements

-   Normalize "ressponded" to "responded" across validations and UI.
-   Add drag-and-drop reordering for image uploads.
-   Add delete/edit UI for MediaCase and MediaReport items.
-   Add pagination/search in dashboards.
-   Add policies/permissions for tighter access control.
