<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\Member;
use App\Models\Package;
use App\Models\Pin;
use App\Models\PinRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class PinRequestFactory extends Factory
{
    protected $model = PinRequest::class;

    public function definition(): array
    {
        return [
            'member_id' => function () {
                if (! $member = Member::inRandomOrder()->first()) {
                    $member = Member::factory()->create();
                }

                return $member->id;
            },
            'package_id' => function () {
                if (! $member = Package::inRandomOrder()->first()) {
                    $member = Package::factory()->create();
                }

                return $member->id;
            },
            'bank_id' => function () {
                if (! $bank = Bank::inRandomOrder()->first()) {
                    $bank = Bank::factory()->create();
                }

                return $bank->id;
            },
            'no_pins' => $this->faker->randomNumber(2),
            'payment_mode' => $this->faker->randomElement([
                PinRequest::PM_CASH, PinRequest::PM_NEFT, PinRequest::PM_RTGS, PinRequest::PM_UPI, PinRequest::PM_CHEQUE,
                PinRequest::PM_DEMAND_DRAFT, PinRequest::PM_PAYTM, PinRequest::PM_OTHER,
            ]),
            'reference_no' => Str::random(),
            'deposit_date' => $this->faker->dateTimeBetween('-1 year'),
            'status' => $this->faker->randomElement([
                PinRequest::STATUS_PENDING, PinRequest::STATUS_APPROVED, PinRequest::STATUS_REJECTED,
            ]),
        ];
    }

    public function configure(): PinRequestFactory
    {
        return $this->afterCreating(function (PinRequest $pinRequest) {
            if ($pinRequest->isApproved()) {
                for ($i = 0; $i < $pinRequest->no_pins; $i++) {
                    Pin::create([
                        'pin_request_id' => $pinRequest->id,
                        'package_id' => $pinRequest->package_id,
                        'member_id' => $pinRequest->member_id,
                        'code' => strtoupper(Str::random(10)),
                        'amount' => $pinRequest->package->amount,
                        'status' => Pin::STATUS_UN_USED,
                    ]);
                }
            }

            if (env('APP_ENV') != 'local') {
                $pinRequest->addMedia(database_path('seeders/assets/pan-card.jpeg'))
                    ->preservingOriginal()
                    ->toMediaCollection(PinRequest::MC_RECEIPT);
            }
        });
    }
}
