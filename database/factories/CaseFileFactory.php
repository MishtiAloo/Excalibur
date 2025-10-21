<?php

namespace Database\Factories;

use App\Models\CaseFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CaseFile>
 */
class CaseFileFactory extends Factory
{
    protected $model = CaseFile::class;

    public function definition(): array
    {
        return [
            'case_type' => $this->faker->randomElement(['missing']),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'coverage_lat' => fake()->latitude(22, 27),
            'coverage_lng' => fake()->longitude(88, 92),
            'coverage_radius' => $this->faker->numberBetween(100, 5000),
            'status' => $this->faker->randomElement(['active','under_investigation','resolved','closed']),
            'urgency' => $this->faker->randomElement(['low','medium','high','critical','national']),
        ];
    }
}
