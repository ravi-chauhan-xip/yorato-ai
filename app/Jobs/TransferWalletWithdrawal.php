<?php

namespace App\Jobs;

use App\Models\CompanyWallet;
use App\Models\IncomeWithdrawalRequest;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use DB;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use phpseclib\Math\BigInteger;
use RuntimeException;
use Sentry;
use Throwable;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3p\EthereumTx\Transaction;

class TransferWalletWithdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 300;

    private CompanyWallet $companyWallet;

    private IncomeWithdrawalRequest $walletWithdrawal;

    /**
     * Create a new job instance.
     *
     *
     * @return void
     */
    public function __construct(CompanyWallet $companyWallet, IncomeWithdrawalRequest $walletWithdrawal)
    {
        $this->companyWallet = $companyWallet;
        $this->walletWithdrawal = $walletWithdrawal;
    }

    /**
     * Execute the job.
     *
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        if (
            $this->companyWallet->isLocked() &&
            $this->companyWallet->isActive()
            && $this->walletWithdrawal->isProcessing()
            && $this->walletWithdrawal->isBlockchainStatusProcessing()
        ) {
            DB::transaction(function () {
                try {
                    if ($this->walletWithdrawal->member->isFree()) {
                        $this->companyWallet->unLockWallet();

                        $this->walletWithdrawal->update([
                            'blockchain_status' => IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_FAILED,
                            'error' => 'Blockchain Transfer halted as user was Free or Blocked',
                        ]);

                        ProcessWalletWithdrawals::dispatch($this->walletWithdrawal);

                        return;
                    }

                    $decimal = env('USDT_CONTRACT_DECIMAL');
                    $contractAddress = env('USDT_CONTRACT_ADDRESS');
                    $abi = config('smart-contract.usdt-abi');

                    $totalBD = BigDecimal::of($this->walletWithdrawal->total)
                        ->multipliedBy(10 ** $decimal)
                        ->toScale(0, RoundingMode::HALF_EVEN);

                    $companyPublicKey = $this->companyWallet->address;
                    $companyPrivateKey = $this->companyWallet->private_key;
                    $bscRPCUrl = env('BSC_RPC_URL');

                    $httpProvider = new HttpProvider(new HttpRequestManager($bscRPCUrl, 15));

                    $contract = new Contract($httpProvider, $abi);
                    $companyBalanceBD = BigDecimal::of(0);
                    $contract->at($contractAddress)->call('balanceOf', $this->companyWallet->address, function ($error, $balance) use (&$companyBalanceBD) {
                        if ($error) {
                            throw new RuntimeException($error->message());
                        }
                        $companyBalanceBD = BigDecimal::of($balance[0]->toString());
                    });

                    if ($companyBalanceBD->isLessThan($totalBD)) {
                        throw new RuntimeException("Insufficient balance to process Withdrawal {$this->walletWithdrawal->id}");
                    }

                    $rawTransactionData = '0x'.$contract->getData(
                        'transfer',
                        $this->walletWithdrawal->to_address,
                        (string) $totalBD
                    );

                    $nonce = null;
                    $contract->eth->getTransactionCount($companyPublicKey, function ($error, $nonceResult) use (&$nonce) {
                        if ($error) {
                            throw new RuntimeException($error->getMessage());
                        }
                        $nonce = $nonceResult;
                    });

                    $transactionParams = [
                        'nonce' => '0x'.dechex($nonce->toString()),
                        'from' => $companyPublicKey,
                        'to' => $contractAddress,
                        'value' => '0x0',
                        'accessList' => [],
                        'data' => $rawTransactionData,
                    ];
                    $estimatedGas = null;
                    $contract->eth->estimateGas($transactionParams, function ($error, $gas) use (&$estimatedGas) {
                        if ($error) {
                            throw new RuntimeException($error->getMessage());
                        }

                        $estimatedGas = $gas;
                    });

                    $gasPrice = null;
                    $contract->eth->gasPrice(function ($err, $price) use (&$gasPrice) {
                        if ($err !== null) {
                            throw new Exception($err->getMessage());
                        }

                        $gasPrice = $price;
                    });
                    // TODO: Check if enough matic is available to pay for gas

                    $transactionParams['gas'] = '0x'.dechex($estimatedGas->multiply(new BigInteger(2))->toString());
                    $transactionParams['gasPrice'] = '0x'.dechex($gasPrice->toString());
                    $transactionParams['chainId'] = (int) env('BSC_CHAIN_ID');

                    $transaction = new Transaction($transactionParams);

                    $signedTransaction = '0x'.$transaction->sign($companyPrivateKey);
                    $txHash = null;
                    $contract->eth->sendRawTransaction($signedTransaction, function ($error, $txResult) use (&$txHash) {
                        if ($error) {
                            throw new RuntimeException($error->getMessage());
                        }

                        $txHash = $txResult;
                    });

                    $this->walletWithdrawal->update([
                        'tx_hash' => $txHash,
                        'blockchain_status' => IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_VERIFYING,
                    ]);

                    $this->companyWallet->increment('transaction_count');

                    VerifyWalletWithdrawal::dispatch($this->walletWithdrawal)
                        ->delay(now()->addSeconds(5));

                } catch (Throwable $exception) {
                    Sentry::captureException($exception);

                    $this->walletWithdrawal->update([
                        'blockchain_status' => IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_FAILED,
                        'error' => $exception->getMessage(),
                    ]);
                }
            });
        }
    }

    public function failed(Throwable $exception)
    {
        Sentry::captureException($exception);

        $this->walletWithdrawal->update([
            'blockchain_status' => IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_FAILED,
            'error' => $exception->getMessage(),
        ]);
    }
}
