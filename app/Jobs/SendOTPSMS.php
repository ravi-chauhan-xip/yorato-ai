<?php

namespace App\Jobs;

use App\Library\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOTPSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $user;

    private string $otp;

    private string $action;

    public function __construct(array $user, string $otp, string $action)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->action = $action;
    }

    public function handle(): void
    {
        $message = sprintf(
            'Dear %s , We have received a %s request. Please %s with your new OTP %s.',
            $this->user['name'],
            $this->action,
            $this->action,
            $this->otp
        );
        Sms::send($this->user['mobile'], $message);
    }
}
