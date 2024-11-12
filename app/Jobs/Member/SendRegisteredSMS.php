<?php

namespace App\Jobs\Member;

use App\Library\Sms;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laracasts\Presenter\Exceptions\PresenterException;

class SendRegisteredSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Member $member;

    private string $password;

    private string $financialPassword;

    public function __construct(Member $member, string $password, string $financialPassword)
    {
        $this->member = $member;
        $this->password = $password;
        $this->financialPassword = $financialPassword;
    }

    /**
     * @throws PresenterException
     */
    public function handle(): void
    {
        $message = sprintf(
            'Welcome , Dear %s, USER ID: %s, Login Password: %s, Ref. link: %s',
            $this->member->user->name,
            $this->member->code,
            $this->password,
            $this->member->present()->referralLink(),
        );

        Sms::send($this->member->user->mobile, $message);
    }
}
