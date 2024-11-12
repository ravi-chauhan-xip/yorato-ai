<?php

namespace App\Jobs\Admin;

use App\Models\BinaryMatch;
use App\Models\Member;
use App\Models\Payout;
use App\Models\PayoutMember;
use App\Models\User;
use App\Models\WalletTransaction;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GeneratePayout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Payout $payout;

    private array $memberIds;

    /**
     * Create a new job instance.
     */
    public function __construct(Payout $payout, array $memberIds)
    {
        $this->payout = $payout;
        $this->memberIds = $memberIds;
    }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        DB::transaction(function () {
            Member::eligibleForPayout()
                ->whereIn('id', $this->memberIds)
                ->where('wallet_balance', '>=', 100)
                ->chunkById(1000, function ($members) {
                    foreach ($members as $member) {
                        $payoutMember = PayoutMember::create([
                            'member_id' => $member->id,
                            'payout_id' => $this->payout->id,
                            'amount' => 0,
                            'tds' => 0,
                            'admin_charge' => 0,
                            'total' => 0,
                            'comment' => '',
                            'status' => PayoutMember::STATUS_PENDING,
                        ]);

                        $member->walletTransactions()
                            ->whereNull('payout_member_id')
                            ->where('responsible_type', '!=', PayoutMember::class)
                            ->chunkById(1000, function ($walletTransactions) use (&$payoutMember) {

                                /** @var WalletTransaction $walletTransaction */
                                foreach ($walletTransactions as $walletTransaction) {
                                    if ($walletTransaction->type == WalletTransaction::TYPE_CREDIT) {
                                        if ($walletTransaction->responsible_type == BinaryMatch::class) {
                                            $payoutMember->binary_income += $walletTransaction->amount;
                                            $this->payout->binary_income += $walletTransaction->amount;
                                        }
                                    }

                                    if ($walletTransaction->responsible_type == User::class) {
                                        if ($walletTransaction->type == WalletTransaction::TYPE_CREDIT) {
                                            $payoutMember->admin_credit += $walletTransaction->amount;
                                            $this->payout->admin_credit += $walletTransaction->amount;
                                        } else {
                                            $payoutMember->admin_debit += $walletTransaction->amount;
                                            $this->payout->admin_debit += $walletTransaction->amount;
                                        }
                                    }

                                    if ($walletTransaction->type == WalletTransaction::TYPE_CREDIT) {
                                        $payoutMember->amount += $walletTransaction->amount;

                                        $this->payout->amount += $walletTransaction->amount;
                                    } else {
                                        $payoutMember->amount -= $walletTransaction->amount;

                                        $this->payout->amount -= $walletTransaction->amount;
                                    }

                                    $walletTransaction->payout_member_id = $payoutMember->id;
                                    $walletTransaction->save();
                                }

                                $adminChargePercent = settings('admin_charge_percent');
                                $adminCharge = $payoutMember->amount * $adminChargePercent / 100;
                                $total = $payoutMember->amount - $adminCharge;

                                $tdsPercent = settings('tds_percent');
                                $tds = $total * $tdsPercent / 100;
                                $payableAmount = $total - $tds;

                                $payoutMember->admin_charge = $adminCharge;
                                $payoutMember->total = $total;
                                $payoutMember->tds = $tds;

                                $payoutMember->payable_amount = $payableAmount;

                                $this->payout->admin_charge += $payoutMember->admin_charge;
                                $this->payout->total += $payoutMember->total;
                                $this->payout->tds += $payoutMember->tds;
                                $this->payout->payable_amount += $payoutMember->payable_amount;
                            });
                        $payoutMember->save();

                        $payoutMember->update([
                            'status' => PayoutMember::STATUS_COMPLETE,
                            'comment' => 'Payout Generated',
                        ]);

                        $amount = $payoutMember->amount;

                        $member->walletTransactions()->create([
                            'opening_balance' => $amount,
                            'closing_balance' => 0,
                            'amount' => $amount,
                            'tds' => 0.00,
                            'admin_charge' => 0.00,
                            'total' => $amount,
                            'type' => WalletTransaction::TYPE_DEBIT,
                            'responsible_id' => $payoutMember->id,
                            'responsible_type' => PayoutMember::class,
                            'comment' => 'Payout Generated',
                        ]);
                    }
                });

            $this->payout->status = Payout::STATUS_COMPLETED;
            $this->payout->save();

            $this->payout->payoutMembers()
                ->chunkById(1000, function ($payoutMembers) {
                    foreach ($payoutMembers as $payoutMember) {
                        if (settings('sms_enabled')) {
                            SendPayoutSMS::dispatch($payoutMember);
                        }
                    }
                });
        });
    }
}
