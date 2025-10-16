<?php

namespace Database\Factories;

use App\Models\Instruction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Instruction>
 */
class InstructionFactory extends Factory
{
    protected $model = Instruction::class;

    public function definition(): array
    {
        return [
            'details' => $this->faker->sentence(),
            'issued_at' => now(),
        ];
    }
}
