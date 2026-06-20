<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Nation;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'nation_id' => Nation::factory(),
            'category_id' => Category::factory(),
            'name' => 'Tank ' . fake()->unique()->bothify('??-###'),
            'image' => null,
            'model_file' => null,
            'production_year' => fake()->numberBetween(1939, 1945),
            'quantity' => fake()->numberBetween(1, 5000),
            'battles' => fake()->city(),
            'description' => fake()->sentence(10),
        ];
    }
}
