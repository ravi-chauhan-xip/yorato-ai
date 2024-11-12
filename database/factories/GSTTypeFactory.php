<?php

namespace Database\Factories;

use App\Models\GSTType;
use Illuminate\Database\Eloquent\Factories\Factory;

class GSTTypeFactory extends Factory
{
    protected $model = GSTType::class;

    public function definition(): array
    {
        $cgst = $this->faker->randomNumber(1, true);
        $sgst = $this->faker->randomNumber(1, true);

        return [
            'hsn_code' => $this->faker->randomNumber($nbDigits = 4, $strict = true),
            'sgst' => $sgst,
            'cgst' => $cgst,
            'gst' => $cgst + $sgst,
        ];
    }
}
