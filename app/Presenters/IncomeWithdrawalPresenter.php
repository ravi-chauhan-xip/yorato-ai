<?php

namespace App\Presenters;

use App\Models\IncomeWithdrawalRequest;
use Laracasts\Presenter\Presenter;

/**
 * Class PinRequestPresenter
 */
class IncomeWithdrawalPresenter extends Presenter
{
    public function status(): string
    {
        return IncomeWithdrawalRequest::STATUSES[$this->entity->status];
    }
}
