<?php

namespace App\Jobs;

use App\Models\Member;
use App\Models\WalletTransaction;
use App\Traits\CoinTrait;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

class MatchStakeOnNetwork implements ShouldQueue
{
    use CoinTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Member $member;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->member->id))
                ->releaseAfter(60)
                ->expireAfter(180),
        ];
    }

    /**
     * @return void
     *
     * @throws Throwable
     */
    public function handle()
    {
        DB::transaction(function () {
            //            if ((
            //                Member::where('sponsor_id', $this->member->id)
            //                    ->where('path', 'like', '%'.$this->member->left_id.'/%')
            //                    ->where('status', Member::STATUS_ACTIVE)
            //                    ->where('activated_at', '>=', Carbon::parse($this->member->activated_at))
            //                    ->count() +
            //                Member::whereNotNull('package_id')
            //                    ->where('sponsor_id', $this->member->id)
            //                    ->where('id', $this->member->left_id)
            //                    ->where('status', Member::STATUS_ACTIVE)
            //                    ->where('activated_at', '>=', Carbon::parse($this->member->activated_at))
            //                    ->count()
            //            ) > 0 &&
            //            (
            //                Member::where('sponsor_id', $this->member->id)
            //                    ->where('path', 'like', '%'.$this->member->right_id.'/%')
            //                    ->where('status', Member::STATUS_ACTIVE)
            //                    ->where('activated_at', '>=', Carbon::parse($this->member->activated_at))
            //                    ->count() +
            //                Member::whereNotNull('package_id')
            //                    ->where('sponsor_id', $this->member->id)
            //                    ->where('id', $this->member->right_id)
            //                    ->where('status', Member::STATUS_ACTIVE)
            //                    ->where('activated_at', '>=', Carbon::parse($this->member->activated_at))
            //                    ->count()
            //            ) > 0
            //            ) {
            while (
                ($this->member->left_stake_power >= settings('min_matching') && $this->member->right_stake_power >= settings('min_matching'))
            ) {
                $paidBinaryCount = $this->member->binaryMatches()->count();
                // Match any PV of this is not the first binary income
                if ($paidBinaryCount >= 0) {
                    if (($this->member->left_stake_power >= settings('min_matching') && $this->member->right_stake_power >= settings('min_matching'))) {
                        if ($this->member->left_stake_power >= $this->member->right_stake_power) {
                            $pvMatch = $this->member->right_stake_power;
                        } else {
                            $pvMatch = $this->member->left_stake_power;
                        }

                        $this->member->left_stake_power -= $pvMatch;
                        $left = $pvMatch;
                        $this->member->right_stake_power -= $pvMatch;
                        $right = $pvMatch;
                    } else {
                        break;
                    }
                } else {
                    // If the member doesn't not have 2:1 PV and the member also has no previous binary income then ignore that member
                    break;
                }

                $this->createBinaryTransaction($this->member, $left, $right, $pvMatch);
                $this->member->save();
            }
            //            }
        });
    }

    public function createBinaryTransaction(Member &$member, $left, $right, $pvMatch)
    {
        DB::transaction(function () use ($pvMatch, $right, $left, $member) {
            $lastMatchingBonusIncome = $member->stakingBinaryMatches()->orderBy('id', 'desc')->first();

            $cappingReached = false;
            $amount = $pvMatch * 5 / 100;
            $comment = 'Team matching staking income of '.toHumanReadable($left).':'.toHumanReadable($right).' '.env('APP_CURRENCY_USDT').' matching through the staking';

            $cappingRemaining = $member->cappingRemaining();

            if ($member->package->capping > 0 && isset($cappingRemaining)) {
                if ($cappingRemaining <= 0) {
                    $cappingReached = true;
                    $amount = 0;
                    $comment = $comment.' flushed due to capping.';
                } else {
                    if ($amount > $cappingRemaining) {
                        $amount = $cappingRemaining;
                        $comment = $comment.' partial capping reached.';
                    }
                }
            }

            if (! $this->member->isActive()) {
                $amount = 0;
                $comment = "$comment flush due to member not active.";
            }

            $isShow = true;

            $income = $member->stakingBinaryMatches()->create([
                'amount' => $amount,
                'admin_charge' => 0,
                'tds' => 0,
                'total' => $amount,
                'capping_reached' => $cappingReached,
                'left_members' => $member->left_count,
                'right_members' => $member->right_count,
                'left_total_bv' => $member->left_stake_bv,
                'right_total_bv' => $member->right_stake_bv,
                'left_forward_bv' => $member->left_stake_power,
                'right_forward_bv' => $member->right_stake_power,
                'left_new_bv' => $member->left_stake_bv - optional($lastMatchingBonusIncome)->left_total_bv,
                'right_new_bv' => $member->right_stake_bv - optional($lastMatchingBonusIncome)->right_total_bv,
                'left_completed_bv' => $left,
                'right_completed_bv' => $right,
                'is_show' => $isShow,
            ]);

            $income->walletTransaction()->create([
                'member_id' => $member->id,
                'opening_balance' => $member->wallet_balance,
                'closing_balance' => $member->wallet_balance + $amount,
                'amount' => $amount,
                'tds' => 0,
                'admin_charge' => 0,
                'total' => $amount,
                'comment' => $comment,
                'type' => WalletTransaction::TYPE_CREDIT,
            ]);

            $member->save();

            CalculateLeadershipIncome::dispatch($income);
        });
    }
}
