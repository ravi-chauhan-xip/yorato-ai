<?php

namespace App\Presenters;

use App\Models\Bank;
use Laracasts\Presenter\Presenter;

/**
 * Class BankPresenter
 */
class BankPresenter extends Presenter
{
    public function acType(): string
    {
        return Bank::TYPES[$this->entity->ac_type];
    }

    public function status(): string
    {
        return Bank::STATUSES[$this->entity->status];
    }
}
