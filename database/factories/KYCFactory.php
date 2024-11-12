<?php

namespace Database\Factories;

use App\Models\KYC;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class KYCFactory extends Factory
{
    protected $model = KYC::class;

    public function definition(): array
    {
        return [
            'member_id' => function () {
                if (! $member = Member::inRandomOrder()->first()) {
                    $member = Member::factory()->create();
                }

                $member->kyc()->delete();

                return $member->id;
            },
            'pan_card' => $this->faker->regexify('/^[A-Z]{5}[0-9]{4}[A-Z]$/'),
            'aadhaar_card' => $this->faker->regexify('/^[1-9]{1}[0-9]{11}$/'),
            'bank_name' => $this->faker->randomElement([
                'ICICI', 'SBI', 'Kotak Mahindra', 'Bank Of Baroda',
            ]),
            'bank_branch' => $this->faker->randomElement([
                'Baroda Main', 'Fatehgunj', 'Alkapuri', 'O.P. Road', 'Jetalpur Road',
            ]),
            'bank_ifsc' => $this->faker->regexify('/^[A-Z]{4}[0-9]{7}$/'),
            'account_type' => $this->faker->randomElement([KYC::ACCOUNT_TYPE_SAVING, KYC::ACCOUNT_TYPE_CURRENT]),
            'account_name' => function ($kyc) {
                return Member::find($kyc['member_id'])->user->name;
            },
            'account_number' => $this->faker->regexify('/^[1-9]{1}[0-9]{15}$/'),
            'status' => $this->faker->randomElement([KYC::STATUS_APPROVED, KYC::STATUS_PENDING, KYC::STATUS_REJECTED]),
        ];
    }

    public function configure(): KYCFactory
    {
        return $this->afterCreating(function (KYC $kyc) {
            if (env('APP_ENV') != 'production') {
                if (mt_rand(0, 1)) {
                    $kyc->addMedia(database_path('seeders/assets/pan-card.jpeg'))
                        ->preservingOriginal()
                        ->toMediaCollection(KYC::MC_PAN_CARD);
                }

                if (mt_rand(0, 1)) {
                    $kyc->addMedia(database_path('seeders/assets/aadhaar-card.jpeg'))
                        ->preservingOriginal()
                        ->toMediaCollection(KYC::MC_AADHAAR_CARD);
                }

                if (mt_rand(0, 1)) {
                    $kyc->addMedia(database_path('seeders/assets/cancel-cheque.jpeg'))
                        ->preservingOriginal()
                        ->toMediaCollection(KYC::MC_CANCEL_CHEQUE);
                }
            }
        });
    }
}
