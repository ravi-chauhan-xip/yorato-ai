<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
{
    protected $model = Bank::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'ICICI', 'SBI', 'Kotak Mahindra', 'Bank Of Baroda',
            ]),
            'branch_name' => $this->faker->randomElement([
                'Baroda Main', 'Fatehgunj', 'Alkapuri', 'O.P. Road', 'Jetalpur Road',
            ]),
            'account_holder_name' => $this->faker->name,
            'ac_number' => $this->faker->regexify('/^[1-9]{1}[0-9]{15}$/'),
            'ifsc' => $this->faker->regexify('/^[A-Z]{4}[0-9]{7}$/'),
            'ac_type' => $this->faker->randomElement([Bank::ACCOUNT_TYPE_SAVING, Bank::ACCOUNT_TYPE_CURRENT]),
        ];
    }
}
