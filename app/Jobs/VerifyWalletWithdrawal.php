<?php

namespace App\Jobs;

use App\Models\IncomeWithdrawalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

class VerifyWalletWithdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 300;

    private IncomeWithdrawalRequest $walletWithdrawal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(IncomeWithdrawalRequest $walletWithdrawal)
    {
        $this->walletWithdrawal = $walletWithdrawal;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->walletWithdrawal->blockchain_status === IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_VERIFYING) {
            $bscRPCUrl = env('BSC_RPC_URL');

            $httpProvider = new HttpProvider(new HttpRequestManager($bscRPCUrl, 15));

            $abi = config('smart-contract.usdt-abi');

            $contract = new Contract($httpProvider, $abi);

            $txHash = $this->walletWithdrawal->tx_hash;
            $txReceipt = null;

            $secondsPastTransaction = 0;
            $secondsToWaitForTransaction = 60;

            while ($txReceipt === null) {
                $contract->eth->getTransactionReceipt($txHash, function ($error, $receipt) use (&$txReceipt) {
                    if ($error) {
                        throw new RuntimeException($error);
                    }

                    if ($receipt && $receipt->status === '0x1') {
                        $txReceipt = $receipt;
                    }
                });

                sleep(1);

                $secondsPastTransaction++;

                if ($secondsPastTransaction >= $secondsToWaitForTransaction) {
                    break;
                }
            }

            if ($txReceipt) {
                $this->walletWithdrawal->companyWallet->unLockWallet();

                $this->walletWithdrawal->update([
                    'receipt' => $txReceipt,
                    'status' => IncomeWithdrawalRequest::STATUS_COMPLETED,
                    'blockchain_status' => IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_SUCCESS,
                ]);

                UpdateCompanyWalletBalance::dispatch($this->walletWithdrawal->companyWallet, $this->walletWithdrawal);
            } else {
                VerifyWalletWithdrawal::dispatch($this->walletWithdrawal)
                    ->delay(now()->addSeconds(5));
            }
        }
    }
}
