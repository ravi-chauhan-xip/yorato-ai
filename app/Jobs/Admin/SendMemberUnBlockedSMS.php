<?php

namespace App\Jobs\Admin;

use App\Library\Sms;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMemberUnBlockedSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Member $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function handle(): void
    {
        $msg = sprintf(
            'Dear %s, your ID %s has been Unblocked by admin. For more information contact the admin office.',
            $this->member->user->name,
            $this->member->code
        );

        Sms::send($this->member->user->mobile, $msg);
    }
}
