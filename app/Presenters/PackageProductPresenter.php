<?php

namespace App\Presenters;

use App\Models\PackageProduct;
use Laracasts\Presenter\Presenter;

/**
 * Class PackageProductPresenter
 */
class PackageProductPresenter extends Presenter
{
    public function price(): string
    {
        return '₹ '.$this->entity->price;
    }

    public function gstSlab(): string
    {
        return PackageProduct::GST_SLABS[$this->entity->gst_slab];
    }
}
