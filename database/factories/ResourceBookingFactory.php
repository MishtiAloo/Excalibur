<?php

namespace Database\Factories;

use App\Models\ResourceBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceBooking>
 */
class ResourceBookingFactory extends Factory
{
    protected $model = ResourceBooking::class;

    public function definition(): array
    {
        return [
            'check_out_time' => now(),
            'check_in_time' => null,
        ];
    }
}
