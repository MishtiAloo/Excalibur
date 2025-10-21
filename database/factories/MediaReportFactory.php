<?php

namespace Database\Factories;

use App\Models\MediaReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaReport>
 */
class MediaReportFactory extends Factory
{
    protected $model = MediaReport::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence(),
        ];
    }
}
