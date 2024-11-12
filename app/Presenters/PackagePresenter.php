<?php

namespace App\Presenters;

use App\Models\Package;
use Laracasts\Presenter\Presenter;

/**
 * Class PackagePresenter
 */
class PackagePresenter extends Presenter
{
    public function nameAndAmount(): string
    {
        return sprintf(
            '%s (%s %s)',
            $this->entity->name,
            env('APP_CURRENCY'),
            toHumanReadable($this->entity->amount)
        );
    }

    public function status(): string
    {
        return Package::STATUSES[$this->entity->status];
    }
}
