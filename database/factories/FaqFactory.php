<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'question' => $this->faker->realText(100),
            'answer' => $this->faker->realText(300),
            'status' => $this->faker->randomElement([Faq::STATUS_ACTIVE, Faq::STATUS_INACTIVE]),
        ];
    }
}
