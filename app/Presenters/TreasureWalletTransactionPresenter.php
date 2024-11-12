<?php

namespace App\Presenters;

use App\Models\TreasureWalletTransaction;
use Laracasts\Presenter\Presenter;

/**
 * Class WalletTransactionPresenter
 */
class TreasureWalletTransactionPresenter extends Presenter
{
    public function type(): string
    {
        return TreasureWalletTransaction::TYPES[$this->entity->type];
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
