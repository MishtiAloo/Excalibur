<?php

namespace Database\Factories;

use App\Models\ResourceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceItem>
 */
class ResourceItemFactory extends Factory
{
    protected $model = ResourceItem::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Drone','Boat','First Aid Kit','Radio','Rope']),
            'stored_lat' => $this->faker->latitude(),
            'stored_lng' => $this->faker->longitude(),
            'condition' => $this->faker->randomElement(['new','good','moderate','old']),
            'availability' => $this->faker->randomElement(['available','in_use','delayed_checkout','under_maintenance']),
            'count' => $this->faker->numberBetween(1, 20),
            'availableCount' => $this->faker->numberBetween(0, 20),
        ];
    }
}
