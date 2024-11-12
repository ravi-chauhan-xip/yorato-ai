<?php

namespace App\Presenters;

use App\Models\Order;
use Laracasts\Presenter\Presenter;

/**
 * Class OrderPresenter
 */
class OrdersPresenter extends Presenter
{
    public function status(): string
    {
        return Order::STATUSES[$this->entity->status];
    }

    public function payBy(): string
    {
        return Order::PAY_BY_STATUSES[$this->entity->pay_by];
    }

    public function paymentStatus(): string
    {
        return Order::PAYMENT_STATUSES[$this->entity->payment_status];
    }

    public function primeOrder(): string
    {
        return Order::PRIME_ORDER[$this->entity->is_prime_order];
    }
}
