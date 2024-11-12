<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\BinaryMatch;
use App\Models\DirectSponsorStakingIncome;
use App\Models\DirectWalletIncome;
use App\Models\IncomeWithdrawalRequest;
use App\Models\LeadershipIncome;
use App\Models\MemberStat;
use App\Models\StakingBinaryMatch;
use App\Models\StakingIncome;
use App\Models\UserDeposit;
use App\Models\WalletTransaction;

class WalletTransactionObserver
{
    /**
     * Handle the wallet transaction "created" event.
     *
     * @return void
     */
    public function created(WalletTransaction $walletTransaction)
    {
        if ($walletTransaction->type == WalletTransaction::TYPE_CREDIT) {
            $walletTransaction->member->increment('wallet_balance', $walletTransaction->total);

            if (MemberStat::whereMemberId($walletTransaction->member_id)->doesntExist()) {
                MemberStat::create([
                    'member_id' => $walletTransaction->member_id,
                ]);
            }

            if (! in_array($walletTransaction->responsible_type, [IncomeWithdrawalRequest::class, UserDeposit::class])) {
                MemberStat::whereMemberId($walletTransaction->member_id)->incrementEach([
                    'all_income' => $walletTransaction->total,
                    'staking_income' => $walletTransaction->responsible_type === StakingIncome::class
                        ? $walletTransaction->total :
                        0,
                    'direct_income' => $walletTransaction->responsible_type === DirectWalletIncome::class
                        ? $walletTransaction->total :
                        0,
                    'direct_sponsor_staking' => $walletTransaction->responsible_type === DirectSponsorStakingIncome::class
                        ? $walletTransaction->total :
                        0,
                    'team_matching' => $walletTransaction->responsible_type === BinaryMatch::class
                        ? $walletTransaction->total :
                        0,
                    'team_matching_staking' => $walletTransaction->responsible_type === StakingBinaryMatch::class
                        ? $walletTransaction->total :
                        0,
                    'leadership_bonus' => $walletTransaction->responsible_type === LeadershipIncome::class
                        ? $walletTransaction->total :
                        0,
                    'admin_credit' => $walletTransaction->responsible_type === Admin::class
                        ? $walletTransaction->total :
                        0,
                ]);
            }
        } elseif ($walletTransaction->type == WalletTransaction::TYPE_DEBIT) {
            $walletTransaction->member->decrement('wallet_balance', $walletTransaction->total);
        }
    }

    /**
     * Handle the wallet transaction "updated" event.
     *
     * @return void
     */
    public function updated(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Handle the wallet transaction "deleted" event.
     *
     * @return void
     */
    public function deleted(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Handle the wallet transaction "restored" event.
     *
     * @return void
     */
    public function restored(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Handle the wallet transaction "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(WalletTransaction $walletTransaction)
    {
        //
    }
}
