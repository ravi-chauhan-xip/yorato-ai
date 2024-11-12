<?php

namespace App\Presenters;

use App\Models\KYC;
use Laracasts\Presenter\Presenter;

/**
 * Class KYCPresenter
 */
class KYCPresenter extends Presenter
{
    public function status(): string
    {
        return KYC::STATUSES[$this->entity->status];
    }
}
