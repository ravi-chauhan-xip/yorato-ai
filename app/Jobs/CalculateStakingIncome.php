<?php

namespace App\Jobs;

use App\Models\StakeCoin;
use App\Models\StakingIncome;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Pagination\Cursor;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CalculateStakingIncome implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Carbon $date;

    private Cursor|null|string $cursor;

    /**
     * Create a new job instance.
     */
    public function __construct(Carbon $date, $cursor = null)
    {
        $this->date = $date;
        $this->cursor = $cursor;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        Carbon::setTestNow($this->date);
        $paginator = StakeCoin::with('member')
            ->whereDoesntHave('stakingIncomes', function ($q) {
                return $q->whereDate('created_at', Carbon::parse($this->date));
            })
            ->whereStatus(StakeCoin::STATUS_ACTIVE)
            ->where('remaining_days', '>', 0)
            ->cursorPaginate(perPage: 25, cursor: $this->cursor);

        $dailyIncomePercentage = 0.266;
        if ($dailyIncomePercentage) {
            $paginator->each(function (StakeCoin $stake) use ($dailyIncomePercentage) {
                \DB::transaction(function () use ($dailyIncomePercentage, $stake) {
                    $stake->lockForUpdate();
                    $amount = $stake->amount * $dailyIncomePercentage / 100;
                    $comment = 'Staking income of '.Carbon::parse($this->date)->format('d-m-Y').' through the staking of '.toHumanReadable($stake->amount).' '.env('APP_CURRENCY_USDT');

                    if (! $stake->member->isActive()) {
                        $amount = 0;
                        $comment = 'Member not active for '.$comment;
                    }

                    $income = StakingIncome::create([
                        'member_id' => $stake->member_id,
                        'stake_coin_id' => $stake->id,
                        'stack_amount' => $stake->amount,
                        'percentage' => $dailyIncomePercentage,
                        'amount' => $amount,
                    ]);

                    $income->walletTransaction()->create([
                        'member_id' => $stake->member->id,
                        'opening_balance' => $stake->member->wallet_balance,
                        'amount' => $amount,
                        'closing_balance' => $stake->member->wallet_balance + $amount,
                        'tds' => 0,
                        'admin_charge' => 0,
                        'total' => $amount,
                        'comment' => $comment,
                        'type' => WalletTransaction::TYPE_CREDIT,
                    ]);

                    $stake->decrement('remaining_days', 1);

                    $stake->refresh();

                    if ($stake->remaining_days <= 0) {
                        $stake->status = StakeCoin::STATUS_FINISH;
                        $stake->save();
                    }
                });
            });
        }

        Carbon::setTestNow();

        if ($paginator->hasMorePages()) {
            CalculateStakingIncome::dispatch($this->date, $paginator->nextCursor())->delay(now()->addSecond());
        }
    }
}
