<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendGeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $user;

    private string $title;

    private string $body;

    public function __construct(array $user, string $title, string $body)
    {
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
    }

    public function build(): static
    {
        return $this->subject($this->title)
            ->view('mails.general-mail', ['user' => $this->user, 'title' => $this->title, 'body' => $this->body]);
    }
}
