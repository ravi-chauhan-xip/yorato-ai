<?php

namespace App\Jobs\Admin;

use App\Library\Sms;
use App\Models\PayoutMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPayoutSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PayoutMember $payoutMember;

    public function __construct(PayoutMember $payoutMember)
    {
        $this->payoutMember = $payoutMember;
    }

    public function handle(): void
    {
        $message = sprintf(
            'Dear %s (%s), Your Payout has been generated of INR %s on %s.',
            $this->payoutMember->member->user->name,
            $this->payoutMember->member->code,
            $this->payoutMember->total,
            $this->payoutMember->updated_at->format('d M, Y')
        );

        Sms::send($this->payoutMember->member->user->mobile, $message);
    }
}
