<?php

namespace App\Jobs\Admin;

use App\Library\Sms;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminCreditDebitSms implements ShouldQueue
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
                'Dear %s, You wallet has been %s with %s on %s. Available Balance: %s.',
                $transaction->member->user->name,
                ($transaction->type == WalletTransaction::TYPE_CREDIT ? 'credited' : 'debited'),
                ('Rs'.' '.$transaction->amount),
                $transaction->created_at->dateTimeFormat(),
                ('RS'.' '.$transaction->member->wallet_balance)
            );

            Sms::send($transaction->member->user->mobile, $msg);
        }
    }
}
