<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('pass'),
            'remember_token' => Str::random(10),
            'nid' => (string) fake()->numerify('#############'),
            'phone' => fake()->phoneNumber(),
            'role' => fake()->randomElement(['citizen','officer','volunteer','specialVolunteer','group_leader']),
            'status' => fake()->randomElement(['active','suspended','inactive']),
            'info_credibility' => fake()->numberBetween(0, 100),
            'responsiveness' => fake()->numberBetween(0, 100),
            'permanent_lat' => fake()->latitude(22, 27),
            'permanent_lng' => fake()->longitude(88, 92),
            'current_lat' => fake()->latitude(22, 27),
            'current_lng' => fake()->longitude(88, 92),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
