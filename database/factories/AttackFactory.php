<?php

namespace Database\Factories;

use App\Models\Attack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attack>
 */
class AttackFactory extends Factory
{
    protected $model = Attack::class;

    public function definition(): array
    {
        return [
            'attack_type' => $this->faker->randomElement(['robbery','assault','terror','other']),
            'victims_count' => $this->faker->numberBetween(0, 10),
            'attacker' => $this->faker->name(),
        ];
    }
}
