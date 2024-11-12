<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class AdminsPresenter extends Presenter
{
    public function status(): string
    {
        if ($this->entity->isActive()) {
            return 'Active';
        } else {
            return 'In-Active';
        }
    }

    public function isSuper(): string
    {
        if ($this->entity->is_super) {
            return 'Yes';
        } else {
            return 'No';
        }
    }
}
