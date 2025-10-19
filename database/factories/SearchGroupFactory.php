<?php

namespace Database\Factories;

use App\Models\SearchGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SearchGroup>
 */
class SearchGroupFactory extends Factory
{
    protected $model = SearchGroup::class;

    public function definition(): array
    {
        $max = $this->faker->optional()->numberBetween(2, 20);
        $available = is_null($max) ? null : $this->faker->numberBetween(0, $max);

        return [
            'type' => $this->faker->randomElement(['citizen','terrainSpecial']),
            'intensity' => $this->faker->randomElement(['basic','rigorous','extreme']),
            'status' => $this->faker->randomElement(['active','paused','completed','time_assigned','time_unassigned']),
            'start_time' => $this->faker->optional()->dateTimeBetween('-2 days', '+1 day'),
            'duration' => $this->faker->optional()->numberBetween(30, 600),
            'report_back_time' => $this->faker->optional()->dateTimeBetween('+1 hour', '+3 days'),
            'max_volunteers' => $max,
            'available_volunteer_slots' => $available,
            'instruction' => $this->faker->optional()->sentence(),
            'allocated_lat' => $this->faker->optional()->latitude(),
            'allocated_lng' => $this->faker->optional()->longitude(),
            'radius' => $this->faker->optional()->numberBetween(50, 5000),
        ];
    }
}
