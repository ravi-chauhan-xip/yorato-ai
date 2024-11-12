<?php

namespace App\Jobs;

use App\Models\LeadershipIncome;
use App\Models\StakingBinaryMatch;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CalculateLeadershipIncome implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private StakingBinaryMatch $stakingBinaryMatch;

    public function __construct(StakingBinaryMatch $stakingBinaryMatch)
    {
        $this->stakingBinaryMatch = $stakingBinaryMatch;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        \DB::transaction(function () {
            $sponsor = $this->stakingBinaryMatch->member->sponsor;
            if ($sponsor) {
                $this->stakingBinaryMatch->load('member.sponsor');
                if (! LeadershipIncome::where('member_id', $sponsor->id)
                    ->where('staking_binary_match_id', $this->stakingBinaryMatch->id)
                    ->exists()) {
                    $percentage = 5;
                    $amount = $this->stakingBinaryMatch->amount * $percentage / 100;
                    $comment = 'Leadership income from '.$this->stakingBinaryMatch->member->user->wallet_address.' through the Team matching staking income of '.toHumanReadable($this->stakingBinaryMatch->amount).' '.env('APP_CURRENCY_USDT');

                    if (! $sponsor->isActive()) {
                        $amount = 0;
                        $comment = 'Member not active for '.$comment;
                    }

                    $income = LeadershipIncome::create([
                        'member_id' => $sponsor->id,
                        'from_member_id' => $this->stakingBinaryMatch->member->id,
                        'staking_binary_match_id' => $this->stakingBinaryMatch->id,
                        'binary_amount' => $this->stakingBinaryMatch->amount,
                        'percentage' => $percentage,
                        'amount' => $amount,
                    ]);

                    $income->walletTransaction()->create([
                        'member_id' => $income->member->id,
                        'opening_balance' => $income->member->wallet_balance,
                        'closing_balance' => $income->member->wallet_balance + $income->amount,
                        'amount' => $income->amount,
                        'admin_charge' => 0,
                        'total' => $income->amount,
                        'comment' => $comment,
                        'type' => WalletTransaction::TYPE_CREDIT,
                    ]);
                }
            }
        });
    }
}
