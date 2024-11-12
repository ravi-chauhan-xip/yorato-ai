<?php

namespace App\Presenters;

use App\Models\News;
use Laracasts\Presenter\Presenter;

/**
 * Class BankPresenter
 */
class NewPresenter extends Presenter
{
    public function status(): string
    {
        return News::STATUSES[$this->entity->status];
    }
}
