<?php

namespace App\Presenters;

use App\Models\PinRequest;
use Laracasts\Presenter\Presenter;

/**
 * Class PinRequestPresenter
 */
class PinRequestPresenter extends Presenter
{
    public function receiptUrl(): string
    {
        return $this->entity->getFirstMediaUrl(PinRequest::MC_RECEIPT);
    }

    public function paymentMode(): string
    {
        return PinRequest::PAYMENT_MODES[$this->entity->payment_mode];
    }

    public function status(): string
    {
        return PinRequest::STATUSES[$this->entity->status];
    }

    public function package(): string
    {
        return "{$this->entity->package->name} ({$this->entity->package->amount})";
    }
}
