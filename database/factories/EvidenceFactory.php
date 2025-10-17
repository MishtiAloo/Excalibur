<?php

namespace Database\Factories;

use App\Models\Evidence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evidence>
 */
class EvidenceFactory extends Factory
{
    protected $model = Evidence::class;

    public function definition(): array
    {
        return [
            'received' => $this->faker->boolean(),
            'received_by' => null,
        ];
    }
}
