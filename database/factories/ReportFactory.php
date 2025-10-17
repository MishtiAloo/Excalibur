<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'report_type' => $this->faker->randomElement(['tip','evidence','sighting','hazard','attack','general']),
            'description' => $this->faker->sentence(),
            'location_lat' => $this->faker->latitude(),
            'location_lng' => $this->faker->longitude(),
            'timestamp' => now(),
            'status' => $this->faker->randomElement(['pending','verified','ressponded','falsed','dismissed']),
        ];
    }
}
