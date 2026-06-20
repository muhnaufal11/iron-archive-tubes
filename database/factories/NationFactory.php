<?php

namespace Database\Factories;

use App\Models\Nation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nation>
 */
class NationFactory extends Factory
{
    protected $model = Nation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->country(),
            'flag' => null,
        ];
    }
}
