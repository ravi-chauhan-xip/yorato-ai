<?php

namespace App\Presenters;

use App\Models\OrderProduct;
use Laracasts\Presenter\Presenter;

/**
 * Class OrderProductPresenter
 */
class OrderProductPresenter extends Presenter
{
    public function imageUrl(): string
    {
        return $this->entity->getFirstMediaUrl(OrderProduct::MC_ORDER_PRODUCT_IMAGE);
    }

    public function status(): string
    {
        return OrderProduct::STATUSES[$this->entity->status];
    }
}
