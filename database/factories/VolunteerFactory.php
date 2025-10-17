<?php

namespace Database\Factories;

use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Volunteer>
 */
class VolunteerFactory extends Factory
{
    protected $model = Volunteer::class;

    public function definition(): array
    {
        return [
            'vetting_status' => $this->faker->randomElement(['pending','approved','rejected']),
            'availability' => $this->faker->randomElement(['available','busy','inactive']),
        ];
    }
}
