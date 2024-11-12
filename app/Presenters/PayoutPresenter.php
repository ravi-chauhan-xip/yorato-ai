<?php

namespace App\Presenters;

use App\Models\Payout;
use Laracasts\Presenter\Presenter;

/**
 * Class PayoutPresenter
 */
class PayoutPresenter extends Presenter
{
    public function status(): string
    {
        return Payout::STATUSES[$this->entity->status];
    }
}
