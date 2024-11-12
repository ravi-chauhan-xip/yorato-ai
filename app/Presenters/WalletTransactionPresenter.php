<?php

namespace App\Presenters;

use App\Models\WalletTransaction;
use Laracasts\Presenter\Presenter;

/**
 * Class WalletTransactionPresenter
 */
class WalletTransactionPresenter extends Presenter
{
    public function type(): string
    {
        return WalletTransaction::TYPES[$this->entity->type];
    }

    public function openingBalance(): string
    {
        return $this->entity->opening_balance;
    }

    public function closingBalance(): string
    {
        return $this->entity->closing_balance;
    }

    public function total(): string
    {
        return $this->entity->total;
    }
}
