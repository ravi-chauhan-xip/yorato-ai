<?php

namespace App\Jobs;

use App\Models\DirectSponsorStakingIncome;
use App\Models\StakeCoin;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CalculateDirectSponsorStakingIncome implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private StakeCoin $stackCoin;

    public function __construct(StakeCoin $stackCoin)
    {
        $this->stackCoin = $stackCoin;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        \DB::transaction(function () {
            $sponsor = $this->stackCoin->member->sponsor;
            if ($sponsor) {
                $this->stackCoin->load('member.sponsor');
                if (! DirectSponsorStakingIncome::where('member_id', $sponsor->id)
                    ->where('stake_coin_id', $this->stackCoin->id)
                    ->exists()) {
                    $percentage = 5;
                    $amount = $this->stackCoin->amount * $percentage / 100;
                    $comment = 'Direct sponsor staking income from '.$this->stackCoin->member->user->wallet_address.' through the staking of '.toHumanReadable($this->stackCoin->amount).' '.env('APP_CURRENCY_USDT');

                    if (! $sponsor->isActive()) {
                        $amount = 0;
                        $comment = 'Member not active for '.$comment;
                    }

                    $income = DirectSponsorStakingIncome::create([
                        'member_id' => $sponsor->id,
                        'from_member_id' => $this->stackCoin->member->id,
                        'stake_coin_id' => $this->stackCoin->id,
                        'stake_amount' => $this->stackCoin->amount,
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
