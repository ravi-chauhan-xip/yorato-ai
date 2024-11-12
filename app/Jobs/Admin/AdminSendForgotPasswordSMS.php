<?php

namespace App\Jobs\Admin;

use App\Library\Sms;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdminSendForgotPasswordSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $password;

    private User|Admin $user;

    public function __construct(Admin $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function handle(): void
    {
        $message = sprintf(
            'Dear User , We have received a reset password request. Please login with your new password %s.',
            $this->password
        );

        Sms::send($this->user->mobile, $message);
    }
}
