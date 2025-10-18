<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index_route()
    {
        $response = $this->get('/users');
        $response->assertStatus(200);
    }

    public function test_cases_index_route()
    {
        $response = $this->get('/cases');
        $response->assertStatus(200);
    }

    public function test_search_groups_index_route()
    {
        $response = $this->get('/search-groups');
        $response->assertStatus(200);
    }

    public function test_reports_index_route()
    {
        $response = $this->get('/reports');
        $response->assertStatus(200);
    }

    public function test_officers_index_route()
    {
        $this->get('/officers')->assertStatus(200);
    }

    public function test_volunteers_index_route()
    {
        $this->get('/volunteers')->assertStatus(200);
    }

    // tips routes removed
}
