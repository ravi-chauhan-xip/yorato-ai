<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->realText(100),
            'description' => $this->faker->realText(300),
            'status' => $this->faker->randomElement([News::STATUS_ACTIVE, News::STATUS_INACTIVE]),
        ];
    }
}
