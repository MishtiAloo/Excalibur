<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Officer, Volunteer, SpecialVolunteer, Skill, CaseFile, SearchGroup, Report, MediaReport, Alert, ResourceItem, ResourceBooking, Notification};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 users
        $users = User::factory(20)->create();

        // Assign roles
        // Officers (first 4)
        $officerUsers = $users->take(4);
        foreach ($officerUsers as $u) {
            $u->role = 'officer';
            $u->save();
            Officer::factory()->create(['officer_id' => $u->id]);
        }

        // Volunteers (next 6)
        $volunteerUsers = $users->slice(4, 6);
        foreach ($volunteerUsers as $u) {
            $u->role = 'volunteer';
            $u->save();
            Volunteer::factory()->create(['volunteer_id' => $u->id]);
        }

        // Special Volunteers (subset of volunteers, up to 3)
        $specialUsers = $volunteerUsers->take(3);
        foreach ($specialUsers as $u) {
            $u->role = 'specialVolunteer';
            $u->save();
            SpecialVolunteer::factory()->create([
                'special_volunteer_id' => $u->id,
                'verified_by_officer' => optional($officerUsers->random())->id,
            ]);
        }

        // Group leaders (next 3)
        $groupLeaders = $users->slice(10, 3);
        foreach ($groupLeaders as $u) {
            $u->role = 'group_leader';
            $u->save();
        }

        // Remaining users are citizens
        $citizens = $users->slice(13);
        foreach ($citizens as $u) {
            $u->role = 'citizen';
            $u->save();
        }


        // Skills
        $skills = Skill::factory(6)->create();
        foreach ($users as $u) {
            $u->skills()->attach($skills->random(2)->pluck('skill_id')->toArray(), [
                'level' => 'intermediate',
                'verified' => true,
            ]);
        }

        // Cases (<=5)
        $cases = CaseFile::factory(10)->create([
            'created_by' => $users->random()->id,
        ]);

        // Search groups per case
        $groups = collect();
        foreach ($cases as $case) {
            $groups = $groups->merge(SearchGroup::factory(2)->create([
                'case_id' => $case->case_id,
                'leader_id' => $users->random()->id,
                'instruction' => fake()->sentence(),
                'allocated_lat' => fake()->latitude(),
                'allocated_lng' => fake()->longitude(),
                'radius' => fake()->numberBetween(50, 5000),
            ]));
        }

        // Attach volunteers to groups
        foreach ($groups as $g) {
            if ($volunteerUsers->count() >= 2) {
                $g->volunteers()->attach($volunteerUsers->take(2)->pluck('id')->toArray());
            }
        }

        // Reports per case
        $reports = collect();
        foreach ($cases as $case) {
            $reports = $reports->merge(Report::factory(2)->create([
                'case_id' => $case->case_id,
                'user_id' => $users->random()->id,
            ]));
        }

        // Media attachments (subtype models removed)
        foreach ($reports as $report) {
            MediaReport::factory()->create([
                'report_id' => $report->report_id,
                'uploaded_by' => $users->random()->id,
            ]);
        }

        // Alerts per case
        foreach ($cases as $case) {
            Alert::factory()->create([
                'case_id' => $case->case_id,
                'approved_by' => optional($officerUsers->random())->id,
            ]);
        }

        // Resources and bookings
        $resources = ResourceItem::factory(5)->create();
        foreach ($resources as $res) {
            ResourceBooking::factory()->create([
                'resource_id' => $res->resource_id,
                'group_id' => optional($groups->random())->group_id,
                'checked_out_by' => $users->random()->id,
            ]);
        }

        // Notifications per user
        foreach ($users as $u) {
            Notification::factory()->create([
                'user_id' => $u->id,
            ]);
        }
    }
}
