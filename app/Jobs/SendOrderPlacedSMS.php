<?php

namespace App\Jobs;

use App\Library\Sms;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderPlacedSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        $message = sprintf(
            'Dear User, Your Order has been successfully placed at %s. Order ID: %s Thank you for choosing us.',
            $this->order->member->user->name,
            $this->order->order_no,
        );

        Sms::send($this->order->member->user->mobile, $message);
    }
}
