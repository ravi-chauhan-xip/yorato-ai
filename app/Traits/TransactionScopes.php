<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TransactionScopes
{
    public function scopeMinAmount(Builder $query, $min): Builder
    {
        return $query->where("{$this->getTable()}.amount", '>=', $min);
    }

    public function scopeMaxAmount(Builder $query, $max): Builder
    {
        return $query->where("{$this->getTable()}.amount", '<=', $max);
    }

    public function scopeMinAdminCharge(Builder $query, $min): Builder
    {
        return $query->where("{$this->getTable()}.admin_charge", '>=', $min);
    }

    public function scopeMaxAdminCharge(Builder $query, $max): Builder
    {
        return $query->where("{$this->getTable()}.admin_charge", '<=', $max);
    }

    public function scopeMinTotal(Builder $query, $min): Builder
    {
        return $query->where("{$this->getTable()}.total", '>=', $min);
    }

    public function scopeMaxTotal(Builder $query, $max): Builder
    {
        return $query->where("{$this->getTable()}.total", '<=', $max);
    }
}
