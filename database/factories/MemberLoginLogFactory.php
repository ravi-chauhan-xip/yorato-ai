<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MemberLoginLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberLoginLogFactory extends Factory
{
    protected $model = MemberLoginLog::class;

    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'ip' => $this->faker->ipv4(),
            'created_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
