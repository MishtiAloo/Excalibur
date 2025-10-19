<?php

namespace Database\Factories;

use App\Models\SearchGroup;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupMemberFactory extends Factory
{
    public function definition(): array
    {
        $group = SearchGroup::inRandomOrder()->first() ?? SearchGroup::factory()->create();
        $volunteer = Volunteer::inRandomOrder()->first() ?? Volunteer::factory()->create();

        return [
            'group_id' => $group->group_id,
            'volunteer_id' => $volunteer->volunteer_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
