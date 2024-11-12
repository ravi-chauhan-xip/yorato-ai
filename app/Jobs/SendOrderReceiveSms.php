<?php

namespace App\Jobs;

use App\Library\Sms;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderReceiveSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $order;

    public function __construct(int $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        if ($order = Order::find($this->order)) {
            $msg = sprintf(
                'Dear %s, We are happy to inform that your order is being processed. Thank you for your association!.',
                $order->member->user->name
            );
            Sms::send($order->member->user->mobile, $msg);
        }
    }
}
