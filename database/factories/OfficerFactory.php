<?php

namespace Database\Factories;

use App\Models\Officer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Officer>
 */
class OfficerFactory extends Factory
{
    protected $model = Officer::class;

    public function definition(): array
    {
        return [
            'badge_no' => $this->faker->unique()->bothify('BDG-####'),
            'department' => $this->faker->randomElement(['CID','Patrol','Rescue','Admin']),
            'rank' => $this->faker->randomElement(['Inspector','Sergeant','Constable']),
        ];
    }
}
