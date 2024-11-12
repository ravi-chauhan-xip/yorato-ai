<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $password = $financialPassword = '$2y$10$GjAXIRyTA8hIHBiu5X6EVum3HkXuOMxN4uf7VZKZPhYVve3qZ9w6q';

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'mobile' => $this->faker->regexify('/^[1-9][0-9]{9}$/'),
            'address' => $this->faker->address,
            'pincode' => $this->faker->regexify('/[1-9][0-9]{5}/'),
            'password' => $password,
            'financial_password' => $financialPassword,
        ];
    }
}
