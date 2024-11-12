<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Package;
use App\Models\Pin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class PinFactory extends Factory
{
    protected $model = Pin::class;

    public function definition(): array
    {
        return [
            'package_id' => function () {
                if (! $package = Package::inRandomOrder()->first()) {
                    $package = Package::factory()->create();
                }

                return $package->id;
            },
            'member_id' => function () {
                if (! $member = Member::inRandomOrder()->first()) {
                    $member = Member::factory()->create();
                }

                return $member->id;
            },
            'code' => strtoupper(Str::random(10)),
            'amount' => function ($pin) {
                return Package::find($pin['package_id'])->amount;
            },
        ];
    }
}
