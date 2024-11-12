<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class CategoryPresenter extends Presenter
{
    public function nameAndAmount(): string
    {
        return "{$this->entity->name} ({$this->entity->amount})";
    }

    public function status(): string
    {
        if ($this->entity->isActive()) {
            return 'Active';
        } else {
            return 'In-Active';
        }
    }
}
