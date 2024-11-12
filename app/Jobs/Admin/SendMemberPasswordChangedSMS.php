<?php

namespace App\Jobs\Admin;

use App\Library\Sms;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMemberPasswordChangedSMS implements ShouldQueue
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
        $msg = sprintf(
            'Dear %s, Admin has reset your password and your new password for ID %s is: %s. Visit %s to access your account.',
            $this->member->user->name,
            $this->member->code,
            $this->password,
            route('user.login.create')
        );

        Sms::send($this->member->user->mobile, $msg);
    }
}
