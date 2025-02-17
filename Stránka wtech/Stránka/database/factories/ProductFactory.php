<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create();

        return [
            'name' => $faker->unique()->word,
            'description' => $faker->text,
            'category' => $faker->randomElement([0, 1, 2]),
            'price' => $faker->randomFloat(2, 0, 1000),
            'created_at' => $faker->dateTimeBetween('-1 year', 'now')
        ];
    }
}