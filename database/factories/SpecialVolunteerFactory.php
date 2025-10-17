<?php

namespace Database\Factories;

use App\Models\SpecialVolunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SpecialVolunteer>
 */
class SpecialVolunteerFactory extends Factory
{
    protected $model = SpecialVolunteer::class;

    public function definition(): array
    {
        return [
            'terrain_type' => $this->faker->randomElement(['water','forest','hilltrack','urban']),
            'verified_by_officer' => null,
        ];
    }
}
