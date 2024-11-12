<?php

namespace App\Models\Scopes;

use App\Models\Member;
use App\Models\PayoutMember;
use Illuminate\Database\Eloquent\Builder;

trait MemberScopes
{
    public function scopeEligibleForPayout(Builder $query): Builder
    {
        return $query->active()
            ->paid()
            ->whereHas(function (Builder $query) {
                $query->approved();
            })
            ->whereHas('walletTransactions', function (Builder $query) {
                $query->whereNull('payout_member_id')
                    ->where('responsible_type', '!=', PayoutMember::class);
            });
    }

    /**
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where("{$this->getTable()}.status", self::STATUS_ACTIVE);
    }

    /**
     * @return Builder
     */
    public function scopeNotActive(Builder $query)
    {
        return $query->where("{$this->getTable()}.status", '!=', self::STATUS_ACTIVE);
    }

    /**
     * @return Builder
     */
    public function scopeNotBlocked(Builder $query)
    {
        return $query->where("{$this->getTable()}.status", '!=', self::STATUS_BLOCKED);
    }

    /**
     * @return Builder
     */
    public function scopePaid(Builder $query)
    {
        return $query->whereNotNull("{$this->getTable()}.package_id");
    }

    /**
     * @return Builder
     */
    public function scopeMinBalance(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.wallet_balance", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxBalance(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.wallet_balance", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeChildrenOf(Builder $query, Member $parent)
    {
        return $query->where("{$this->getTable()}.path", 'like', "{$parent->path}/%");
    }

    public function scopeCreatedAtFrom(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeCreatedAtTo(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }

    /**
     * @return Builder
     */
    public function scopeMaxLeftPv(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.left_bv", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinLeftPv(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.left_bv", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxRightPv(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.right_bv", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinRightPv(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.right_bv", '>=', $min);
    }
}
