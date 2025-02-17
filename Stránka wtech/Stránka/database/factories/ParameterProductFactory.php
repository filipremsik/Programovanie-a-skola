<?php

namespace Database\Factories;

use App\Models\ParameterProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class ParameterProductFactory extends Factory
{
    protected $model = ParameterProduct::class;

    public function definition(): array
    {
        $faker = Faker::create();

        return [
            'parameter_id' => $faker->numberBetween(1, 12),
            'product_id' => $faker->numberBetween(1, 50)
        ];
    }
}