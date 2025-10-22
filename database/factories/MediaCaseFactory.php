<?php

namespace Database\Factories;

use App\Models\CaseFile;
use App\Models\MediaCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\MediaCase> */
class MediaCaseFactory extends Factory
{
    protected $model = MediaCase::class;

    public function definition(): array
    {
        return [
            'case_id' => CaseFile::factory(),
            'uploaded_by' => User::factory(),
            'url' => $this->faker->imageUrl(640, 480, 'people', true),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
