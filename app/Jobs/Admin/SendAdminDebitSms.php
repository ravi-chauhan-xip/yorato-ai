<?php

namespace App\Jobs\Admin;

use App\Library\Sms;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminDebitSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $transactionId;

    public function __construct(int $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function handle(): void
    {
        if ($transaction = WalletTransaction::find($this->transactionId)) {
            $msg = sprintf(
                'Dear Member, You wallet has been Debited with %s on. Available Balance: %s.',
                ('Rs'.' '.$transaction->amount),
                ('RS'.' '.$transaction->member->wallet_balance)
            );

            Sms::send($transaction->member->user->mobile, $msg);
        }
    }
}
