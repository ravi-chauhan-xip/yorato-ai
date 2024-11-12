<?php

namespace App\Presenters;

use App\Models\Pin;
use Laracasts\Presenter\Presenter;

/**
 * Class PinPresenter
 */
class PinPresenter extends Presenter
{
    public function status(): string
    {
        return Pin::STATUSES[$this->entity->status];
    }
}
