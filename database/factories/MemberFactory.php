<?php

namespace Database\Factories;

use App\Jobs\AddMemberOnNetwork;
use App\Jobs\UpgradeTopUpOnNetwork;
use App\Models\Member;
use App\Models\Package;
use App\Models\Pin;
use App\Models\PinRequest;
use App\Models\TopUp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'sponsor_id' => function () {
                if ($sponsor = Member::inRandomOrder()->first()) {
                    return $sponsor->id;
                }

                return null;
            },
            'parent_id' => function ($member) {
                if ($sponsor = Member::find($member['sponsor_id'])) {
                    if ($member['parent_side'] == Member::PARENT_SIDE_LEFT) {
                        $parent = $sponsor->extremeLeftMember();
                    } else {
                        $parent = $sponsor->extremeRightMember();
                    }

                    // If the sponsor does not have any children
                    // Then sponsor does not have extremeLeftMember or extremeRightMember
                    // In that case the sponsor is the parent
                    if (! $parent) {
                        $parent = $sponsor;
                    }

                    return $parent;
                }

                return null;
            },
            'level' => function ($member) {
                if ($parent = Member::find($member['parent_id'])) {
                    return $parent->level + 1;
                }

                return 1;
            },
            'parent_side' => $this->faker->randomElement([Member::PARENT_SIDE_LEFT, Member::PARENT_SIDE_RIGHT]),
            'status' => function () {
                $statusChance = mt_rand(0, 99);
                if ($statusChance < 80) {
                    return Member::STATUS_ACTIVE;
                } elseif ($statusChance < 95) {
                    return Member::STATUS_FREE_MEMBER;
                } else {
                    return Member::STATUS_BLOCKED;
                }
            },
            'package_id' => function ($member) {
                if ($member['status'] == Member::STATUS_ACTIVE) {
                    if ($package = Package::inRandomOrder()->first()) {
                        return $package->id;
                    }
                }

                return null;
            },
        ];
    }

    public function configure(): MemberFactory
    {
        return $this->afterCreating(function (Member $member) {
            $member->user->assignRole('member');

            if ($member->sponsor) {
                if ($member->parent_side == Member::PARENT_SIDE_LEFT) {
                    $member->sponsor->increment('sponsored_left');
                } else {
                    $member->sponsor->increment('sponsored_right');
                }
            }

            //            AddMemberOnNetwork::dispatchSync($member);

            if ($member->status == Member::STATUS_ACTIVE) {
                if (mt_rand(0, 99) < 25) {
                    $pinRequest = PinRequest::factory()->create([
                        'member_id' => $member->id,
                        'package_id' => $member->package->id,
                        'no_pins' => 1,
                        'status' => PinRequest::STATUS_APPROVED,
                    ]);
                } else {
                    $pinRequest = null;
                }

                $pin = Pin::factory()->create([
                    'package_id' => $member->package->id,
                    'pin_request_id' => optional($pinRequest)->id,
                    'member_id' => $member->id,
                    'amount' => $member->package->amount,
                    'used_by' => $member->id,
                    'activated_by_id' => $member->id,
                    'activated_by_type' => 2,
                    'used_at' => now(),
                    'status' => Pin::STATUS_USED,
                ]);

                TopUp::factory()->create([
                    'package_id' => $pin->package->id,
                    'amount' => $pin->package->amount,
                    'member_id' => $member->id,
                    'pin_id' => $pin->id,
                ]);

                UpgradeTopUpOnNetwork::dispatch($member);
            }
        });
    }
}
