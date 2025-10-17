<?php

namespace Database\Factories;

use App\Models\Tip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tip>
 */
class TipFactory extends Factory
{
    protected $model = Tip::class;

    public function definition(): array
    {
        return [
            'credibility_score' => $this->faker->numberBetween(0, 100),
            'verified_by' => null,
        ];
    }
}
