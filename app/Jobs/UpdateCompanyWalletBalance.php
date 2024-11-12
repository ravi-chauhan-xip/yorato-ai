<?php

namespace App\Jobs;

use App\Models\CompanyWallet;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Web3;

class UpdateCompanyWalletBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private CompanyWallet $companyWallet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CompanyWallet $companyWallet)
    {
        $this->companyWallet = $companyWallet;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contractAddress = env('USDT_CONTRACT_ADDRESS');
        $abi = config('smart-contract.usdt-abi');

        $bscRPCUrl = env('BSC_RPC_URL');

        $httpProvider = new HttpProvider(new HttpRequestManager($bscRPCUrl, 15));

        $web3 = new Web3($httpProvider);

        $contract = new Contract($httpProvider, $abi);

        $companyBnbBalanceBD = BigDecimal::of(0);

        $web3->eth->getBalance($this->companyWallet->address, function ($error, $balance) use (&$companyBnbBalanceBD) {
            if ($error) {
                throw new RuntimeException($error->message());
            }

            $companyBnbBalanceBD = BigDecimal::of($balance->toString());
        });

        $companyBalanceBD = BigDecimal::of(0);
        $contract->at($contractAddress)->call('balanceOf', $this->companyWallet->address, function ($error, $balance) use (&$companyBalanceBD) {
            if ($error) {
                throw new RuntimeException($error->message());
            }
            $companyBalanceBD = BigDecimal::of($balance[0]->toString());
        });

        $this->companyWallet->update([
            'bnb_balance' => (string) $companyBnbBalanceBD->dividedBy('1000000000000000000', 18, RoundingMode::HALF_EVEN),
        ]);

        $this->companyWallet->update([
            'usdt_balance' => (string) $companyBalanceBD->dividedBy('1000000000000000000', 18, RoundingMode::HALF_EVEN),
        ]);

    }
}
