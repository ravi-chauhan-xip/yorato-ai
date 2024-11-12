<?php

namespace App\Jobs\Member;

use App\Library\Sms;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendForgotPasswordSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Member $member;

    private string $password;

    public function __construct(Member $member, string $password)
    {
        $this->member = $member;
        $this->password = $password;
    }

    public function handle(): void
    {
        $message = sprintf(
            'Dear %s (%s), We have received a reset password request. Please login with your new password %s.',
            $this->member->user->name,
            $this->member->code,
            $this->password
        );

        Sms::send($this->member->user->mobile, $message);
    }
}
