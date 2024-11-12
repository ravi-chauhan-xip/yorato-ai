<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $pv = mt_rand(1, 10);
        $amount = $pv * 1000;

        return [
            'name' => $this->faker->word,
            'amount' => $amount,
            'capping' => $pv * 10000,
            'pv' => $pv,
        ];
    }
}
