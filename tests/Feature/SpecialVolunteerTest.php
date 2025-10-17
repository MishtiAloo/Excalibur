<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Volunteer;

class SpecialVolunteerTest extends TestCase
{
    use RefreshDatabase;

    private function createVolunteerUser(): array
    {
        $user = User::factory()->create(['role' => 'volunteer']);
        $vol = Volunteer::factory()->create(['volunteer_id' => $user->id]);
        return [$user, $vol];
    }

    public function test_create_special_volunteer_with_vetting_status()
    {
        [, $vol] = $this->createVolunteerUser();

        $payload = [
            'special_volunteer_id' => $vol->volunteer_id,
            'terrain_type' => 'forest',
            'vetting_status' => 'approved',
        ];

        $res = $this->postJson('/special-volunteers', $payload);
        $res->assertCreated()->assertJsonFragment([
            'special_volunteer_id' => $vol->volunteer_id,
            'vetting_status' => 'approved',
        ]);
    }

    public function test_create_special_volunteer_invalid_vetting_status()
    {
        [, $vol] = $this->createVolunteerUser();

        $payload = [
            'special_volunteer_id' => $vol->volunteer_id,
            'terrain_type' => 'urban',
            'vetting_status' => 'unknown',
        ];

        $res = $this->postJson('/special-volunteers', $payload);
        $res->assertStatus(422)->assertJsonValidationErrors(['vetting_status']);
    }
}
