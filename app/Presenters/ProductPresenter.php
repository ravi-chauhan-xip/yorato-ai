<?php

namespace App\Presenters;

use App\Models\Product;
use Laracasts\Presenter\Presenter;

/**
 * Class ProductPresenter
 */
class ProductPresenter extends Presenter
{
    public function status(): string
    {
        return Product::STATUSES[$this->entity->status];
    }
}
