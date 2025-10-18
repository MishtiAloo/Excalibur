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
        return [
            'type' => $this->faker->randomElement(['citizen','covert','terrainSpecial']),
            'intensity' => $this->faker->randomElement(['basic','rigorous','extreme']),
            'status' => $this->faker->randomElement(['active','paused','completed']),
            'allocated_time' => $this->faker->numberBetween(1, 240),
            'instruction' => $this->faker->optional()->sentence(),
            'allocated_lat' => $this->faker->optional()->latitude(),
            'allocated_lng' => $this->faker->optional()->longitude(),
            'radius' => $this->faker->optional()->numberBetween(50, 5000),
        ];
    }
}
