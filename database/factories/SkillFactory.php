<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['navigation','drone','boat','mountain','forest','medic','diver','canine_handler','leadership']),
            'description' => $this->faker->sentence(),
        ];
    }
}
