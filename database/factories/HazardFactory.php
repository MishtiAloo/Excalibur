<?php

namespace Database\Factories;

use App\Models\Hazard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hazard>
 */
class HazardFactory extends Factory
{
    protected $model = Hazard::class;

    public function definition(): array
    {
        return [
            'hazard_type' => $this->faker->randomElement(['animal','collapse','chemical']),
            'severity' => $this->faker->randomElement(['low','medium','high']),
        ];
    }
}
