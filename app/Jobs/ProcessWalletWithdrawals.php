<?php

namespace App\Jobs;

use App\Models\CompanyWallet;
use App\Models\IncomeWithdrawalRequest;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessWalletWithdrawals implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private IncomeWithdrawalRequest $withdrawal;

    /**
     * Create a new job instance.
     */
    public function __construct(IncomeWithdrawalRequest $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping(ProcessWalletWithdrawals::class))
                ->dontRelease()
                ->expireAfter(300),
        ];
    }

    /**
     * Execute the job.
     *
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        if ($this->withdrawal->blockchain_status == IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_FAILED
            || $this->withdrawal->blockchain_status == IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_PENDING) {
            DB::transaction(function () {
                CompanyWallet::active()
                    ->unLocked()
                    ->orderBy('transaction_count')
                    ->lockForUpdate()
                    ->eachById(function (CompanyWallet $companyWallet) {
                        $companyWallet->lockWallet();

                        $this->withdrawal->update([
                            'status' => IncomeWithdrawalRequest::STATUS_PROCESSING,
                            'blockchain_status' => IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_PROCESSING,
                            'company_wallet_id' => $companyWallet->id,
                            'from_address' => $companyWallet->address,
                        ]);

                        TransferWalletWithdrawal::dispatch($companyWallet, $this->withdrawal);

                        return true;

                    });
            });
        }
    }
}
