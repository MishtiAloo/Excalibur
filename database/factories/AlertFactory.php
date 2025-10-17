<?php

namespace Database\Factories;

use App\Models\Alert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alert>
 */
class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'alert_type' => $this->faker->randomElement(['amber','silver','red','yellow']),
            'status' => $this->faker->randomElement(['active','expired','cancelled']),
            'expires_at' => now()->addDays(7),
            'message' => $this->faker->sentence(),
        ];
    }
}
