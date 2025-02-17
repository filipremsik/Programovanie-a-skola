<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'login' => $faker->unique()->userName,
            'password' => bcrypt('password'),
            'name' => $faker->name,
            'surname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'address' => $faker->address,
            'phone_number' => Str::replace(' ', '', $faker->unique()->phoneNumber),
            'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            'temporary' => false
        ];
    }
}