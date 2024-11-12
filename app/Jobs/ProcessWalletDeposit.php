<?php

namespace App\Jobs;

use App\Models\Member;
use App\Models\StakeCoin;
use App\Models\TopUp;
use App\Models\UserDeposit;
use App\Models\WalletTransaction;
use App\Traits\CoinTrait;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use DB;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Utils;
use Web3\Web3;

class ProcessWalletDeposit implements ShouldQueue
{
    use CoinTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private UserDeposit $deposit;

    /**
     * Create a new job instance.
     */
    public function __construct(UserDeposit $deposit)
    {
        $this->deposit = $deposit;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $deposit = $this->deposit;
            if (
                UserDeposit::whereId($deposit->id)->lockForUpdate()->exists()
                &&
                $this->deposit->blockchain_status == UserDeposit::BLOCKCHAIN_STATUS_PENDING
            ) {

                $rpcUrl = env('BSC_RPC_URL');
                $httpProvider = new HttpProvider(new HttpRequestManager($rpcUrl, 15));
                $web3 = new Web3($httpProvider);

                $transaction = null;
                $web3->eth->getTransactionByHash($deposit->transaction_hash, function ($err, $receipt) use (&$transaction) {
                    if ($err) {
                        throw new Exception($err->getMessage());
                    }

                    $transaction = $receipt;
                });

                $transactionReceipt = null;
                $web3->eth->getTransactionReceipt($deposit->transaction_hash, function ($err, $receipt) use (&$transactionReceipt) {
                    if ($err) {
                        throw new Exception($err->getMessage());
                    }

                    $transactionReceipt = $receipt;
                });

                if (
                    $transactionReceipt
                    && $transactionReceipt->to
                    && $transactionReceipt->from
                    && $transactionReceipt->logs
                    && count($transactionReceipt->logs) > 0
                    && $transactionReceipt->logs[0]->topics
                    && count($transactionReceipt->logs[0]->topics) > 2
                    && $transactionReceipt->logs[0]->data
                ) {
                    $coinContractAddress = $transactionReceipt->to;
                    $blockNumber = Utils::toBn($transactionReceipt->blockNumber)->toString();
                    $toAddress = strtolower('0x'.substr($transactionReceipt->logs[0]->topics[2], -40));
                    $amount = BigDecimal::of(
                        Utils::toBn('0x'.substr($transactionReceipt->logs[0]->data, 2))->toString()
                    )
                        ->dividedBy(
                            10 ** env('USDT_CONTRACT_DECIMAL'),
                            env('USDT_CONTRACT_DECIMAL'),
                            RoundingMode::HALF_EVEN
                        );

                    if (strtolower(env('VITE_USDT_CONTRACT_ADDRESS')) != strtolower($coinContractAddress)) {
                        $deposit->update([
                            'blockchain_status' => UserDeposit::BLOCKCHAIN_STATUS_FAILED,
                            'remark' => 'Not transfer to '.env('APP_CURRENCY').' contract',
                        ]);

                        return '';
                    }

                    if ($toAddress != strtolower($deposit->to_address)) {
                        $deposit->update([
                            'blockchain_status' => UserDeposit::BLOCKCHAIN_STATUS_FAILED,
                            'remark' => 'Not transfer to correct company deposit address',
                        ]);

                        return '';
                    }

                    if (! $amount->isEqualTo($this->deposit->amount)) {
                        $deposit->update([
                            'blockchain_status' => UserDeposit::BLOCKCHAIN_STATUS_FAILED,
                            'remark' => 'Transfer amount must be  '.$this->deposit->amount,
                        ]);

                        return '';
                    }

                    if (strtolower(env('VITE_USDT_CONTRACT_ADDRESS')) === strtolower($coinContractAddress)
                    ) {
                        $deposit->update([
                            'block_no' => $blockNumber,
                            'blockchain_status' => UserDeposit::BLOCKCHAIN_STATUS_COMPLETED,
                            'receipt' => $transactionReceipt,
                        ]);

                        $deposit->walletTransaction()->create([
                            'member_id' => $deposit->member->id,
                            'opening_balance' => $deposit->member->wallet_balance,
                            'closing_balance' => $deposit->member->wallet_balance + $deposit->amount,
                            'amount' => $deposit->amount,
                            'service_charge' => 0,
                            'total' => $deposit->amount,
                            'comment' => toHumanReadable($deposit->amount).' USDT User Deposited',
                            'type' => WalletTransaction::TYPE_CREDIT,
                        ]);

                        $deposit->load('topUp.package');
                        $deposit->load('stake.package');
                        $deposit->member->refresh();

                        if ($deposit->topUp) {
                            $deposit->topUp->status = TopUp::STATUS_SUCCESS;
                            $deposit->topUp->save();

                            $deposit->topUp->walletTransaction()->create([
                                'member_id' => $deposit->member->id,
                                'opening_balance' => $deposit->member->wallet_balance,
                                'closing_balance' => $deposit->member->wallet_balance - $deposit->amount,
                                'amount' => $deposit->amount,
                                'service_charge' => 0,
                                'total' => $deposit->amount,
                                'comment' => 'Debited '.toHumanReadable($deposit->amount).' USDT for package '.$deposit->topUp->package->name,
                                'type' => WalletTransaction::TYPE_DEBIT,
                            ]);

                            if ($deposit->member->isFree()) {
                                $deposit->member->update([
                                    'is_paid' => Member::IS_PAID,
                                    'status' => Member::STATUS_ACTIVE,
                                    'activated_at' => now(),
                                ]);
                            }

                            $deposit->member->update(['package_id' => $deposit->topUp->package_id]);

                            CalculateDirectWalletIncome::dispatch($deposit->topUp);
                            UpgradeTopUpOnNetwork::dispatch($deposit->topUp);
                        }
                        if ($deposit->stake) {
                            $deposit->stake->status = StakeCoin::STATUS_ACTIVE;
                            $deposit->stake->save();

                            $deposit->stake->walletTransaction()->create([
                                'member_id' => $deposit->member->id,
                                'opening_balance' => $deposit->member->wallet_balance,
                                'closing_balance' => $deposit->member->wallet_balance - $deposit->amount,
                                'amount' => $deposit->amount,
                                'service_charge' => 0,
                                'total' => $deposit->amount,
                                'comment' => 'Debited '.toHumanReadable($deposit->amount).' USDT for stake',
                                'type' => WalletTransaction::TYPE_DEBIT,
                            ]);

                            CalculateDirectSponsorStakingIncome::dispatch($deposit->stake);
                            UpgradeStakeOnNetwork::dispatch($deposit->stake);
                        }

                    }
                }
            }
        }, 5);
    }
}
