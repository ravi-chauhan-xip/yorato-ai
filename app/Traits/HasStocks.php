<?php

namespace App\Traits;

use App\Models\CompanyStockLedger;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStocks
{
    public function companyStockLedger(): MorphMany|CompanyStockLedger
    {
        return $this->morphMany(CompanyStockLedger::class, 'responsible');
    }
}
