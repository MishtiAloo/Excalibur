<?php

namespace Database\Factories;

use App\Models\Sighting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sighting>
 */
class SightingFactory extends Factory
{
    protected $model = Sighting::class;

    public function definition(): array
    {
        return [
            'sighted_person' => $this->faker->name(),
            'time_seen' => now(),
        ];
    }
}
