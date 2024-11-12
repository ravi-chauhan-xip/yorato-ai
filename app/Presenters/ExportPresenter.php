<?php

namespace App\Presenters;

use App\Models\Export;
use Laracasts\Presenter\Presenter;

/**
 * Class BankPresenter
 */
class ExportPresenter extends Presenter
{
    public function status(): string
    {
        return Export::STATUSES[$this->entity->status];
    }
}
