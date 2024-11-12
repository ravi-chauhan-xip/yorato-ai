<?php

namespace App\Jobs;

use App\Models\IncomeWithdrawalRequest;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Web3\Web3;

class ReSyncWithdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 58;

    protected Web3 $web3;

    protected string $transferTopic;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping(self::class))
                ->dontRelease()
                ->expireAfter(59),
        ];
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        IncomeWithdrawalRequest::where('blockchain_status', IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_PENDING)
            ->where('status', IncomeWithdrawalRequest::STATUS_PROCESSING)
            ->each(function ($incomeWithdrawalRequest) {
                ProcessWalletWithdrawals::dispatch($incomeWithdrawalRequest);
            });
    }
}
