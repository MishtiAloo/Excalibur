<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ApiValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_happy_path()
    {
        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'plainpass',
            'role' => 'citizen',
            'status' => 'active',
        ];

        $res = $this->postJson('/users', $payload);
        $res->assertCreated()->assertJsonFragment([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'password' => 'plainpass', // ensure not hashed per requirement
        ]);
    }

    public function test_create_user_validation_errors()
    {
        $res = $this->postJson('/users', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'role' => 'invalid-role',
            'permanent_lat' => 200,
        ]);

        $res->assertStatus(422)->assertJsonValidationErrors([
            'name', 'email', 'password', 'role', 'permanent_lat'
        ]);
    }

    public function test_create_case_happy_path()
    {
        $creator = User::factory()->create();

        $payload = [
            'created_by' => $creator->id,
            'case_type' => 'missing',
            'title' => 'Missing person case',
            'status' => 'active',
            'urgency' => 'medium',
        ];

        $res = $this->postJson('/cases', $payload);
        $res->assertCreated()->assertJsonFragment([
            'case_type' => 'missing',
            'title' => 'Missing person case',
        ]);
    }

    public function test_create_case_validation_errors()
    {
        $res = $this->postJson('/cases', [
            'created_by' => 999999, // not existing
            'case_type' => 'unknown',
            'title' => '',
            'coverage_lat' => -200,
            'coverage_lng' => 400,
            'status' => 'bad',
            'urgency' => 'urgent',
        ]);

        $res->assertStatus(422)->assertJsonValidationErrors([
            'created_by','case_type','title','coverage_lat','coverage_lng','status','urgency'
        ]);
    }

    public function test_create_report_happy_path()
    {
        $creator = User::factory()->create();

        // Create a case via API to ensure case_id exists
        $caseRes = $this->postJson('/cases', [
            'created_by' => $creator->id,
            'case_type' => 'missing',
            'title' => 'Case for report',
        ])->assertCreated();

        $caseId = $caseRes->json('case_id') ?? $caseRes->json('id') ?? $caseRes->json('data.case_id');

        $payload = [
            'case_id' => $caseId,
            'user_id' => $creator->id,
            'report_type' => 'general',
            'description' => 'Initial report',
            'status' => 'pending',
        ];

        $res = $this->postJson('/reports', $payload);
        $res->assertCreated()->assertJsonFragment([
            'report_type' => 'general',
            'description' => 'Initial report',
        ]);
    }

    public function test_create_report_validation_errors()
    {
        $res = $this->postJson('/reports', [
            'case_id' => 0,
            'user_id' => 0,
            'report_type' => 'weird',
            'location_lat' => 1000,
            'location_lng' => -500,
            'timestamp' => 'not-a-date',
            'status' => 'not-valid',
        ]);

        $res->assertStatus(422)->assertJsonValidationErrors([
            'case_id','user_id','report_type','location_lat','location_lng','timestamp','status'
        ]);
    }
}
