<?php

namespace App\Jobs;

use App\Models\DirectWalletIncome;
use App\Models\TopUp;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CalculateDirectWalletIncome implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TopUp $topUp;

    public function __construct(TopUp $topUp)
    {
        $this->topUp = $topUp;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        \DB::transaction(function () {
            $sponsor = $this->topUp->member->sponsor;
            if ($sponsor) {
                $this->topUp->load('member.sponsor');
                if (! DirectWalletIncome::where('member_id', $sponsor->id)
                    ->where('top_up_id', $this->topUp->id)
                    ->exists()) {
                    $percentage = 5;
                    $amount = $this->topUp->amount * $percentage / 100;
                    $comment = 'Direct wallet income from '.$this->topUp->member->user->wallet_address.' through the Top Up of '.toHumanReadable($this->topUp->amount).' '.env('APP_CURRENCY_USDT');

                    if (! $sponsor->isActive()) {
                        $amount = 0;
                        $comment = 'Member not active for '.$comment;
                    }

                    $income = DirectWalletIncome::create([
                        'member_id' => $sponsor->id,
                        'from_member_id' => $this->topUp->member->id,
                        'top_up_id' => $this->topUp->id,
                        'package_amount' => $this->topUp->amount,
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
