<?php

namespace App\Models;

use App\Presenters\WalletTransactionPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\WalletTransaction
 *
 * @property int $id
 * @property int $member_id
 * @property int $responsible_id
 * @property string $responsible_type
 * @property string $opening_balance
 * @property string $closing_balance
 * @property string $amount
 * @property string $admin_charge
 * @property string $total
 * @property int $type 1: Credit, 2: Debit
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read Model|\Eloquent $responsible
 *
 * @method static Builder|WalletTransaction credit()
 * @method static Builder|WalletTransaction eligibleForPayout()
 * @method static Builder|WalletTransaction fromDate(string $date)
 * @method static Builder|WalletTransaction maxAdminCharge($max)
 * @method static Builder|WalletTransaction maxAmount($max)
 * @method static Builder|WalletTransaction maxTds($max)
 * @method static Builder|WalletTransaction maxTotal($max)
 * @method static Builder|WalletTransaction minAdminCharge($min)
 * @method static Builder|WalletTransaction minAmount($min)
 * @method static Builder|WalletTransaction minTds($min)
 * @method static Builder|WalletTransaction minTotal($min)
 * @method static Builder|WalletTransaction newModelQuery()
 * @method static Builder|WalletTransaction newQuery()
 * @method static Builder|WalletTransaction query()
 * @method static Builder|WalletTransaction toDate(string $date)
 * @method static Builder|WalletTransaction whereAdminCharge($value)
 * @method static Builder|WalletTransaction whereAmount($value)
 * @method static Builder|WalletTransaction whereClosingBalance($value)
 * @method static Builder|WalletTransaction whereComment($value)
 * @method static Builder|WalletTransaction whereCreatedAt($value)
 * @method static Builder|WalletTransaction whereId($value)
 * @method static Builder|WalletTransaction whereMemberId($value)
 * @method static Builder|WalletTransaction whereOpeningBalance($value)
 * @method static Builder|WalletTransaction whereResponsibleId($value)
 * @method static Builder|WalletTransaction whereResponsibleType($value)
 * @method static Builder|WalletTransaction whereTotal($value)
 * @method static Builder|WalletTransaction whereType($value)
 * @method static Builder|WalletTransaction whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class WalletTransaction extends Model
{
    use PresentableTrait;

    protected $guarded = ['id'];

    protected string $presenter = WalletTransactionPresenter::class;

    const TYPE_CREDIT = 1;

    const TYPE_DEBIT = 2;

    const TYPES = [
        self::TYPE_CREDIT => 'Credit',
        self::TYPE_DEBIT => 'Debit',
    ];

    public function responsible(): MorphTo
    {
        return $this->morphTo();
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function scopeCredit(Builder $query): Builder
    {
        return $query->where("{$this->getTable()}.type", self::TYPE_CREDIT);
    }

    /**
     * @return Builder
     */
    public function scopeMinAmount(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.amount", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxAmount(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.amount", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinTds(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.tds", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxTds(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.tds", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinAdminCharge(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.admin_charge", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxAdminCharge(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.admin_charge", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinTotal(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.total", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxTotal(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.total", '<=', $max);
    }

    public function scopeFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }

    public function scopeEligibleForPayout(Builder $query): Builder
    {
        return $query->whereNull("{$this->getTable()}.payout_member_id")
            ->where("{$this->getTable()}.responsible_type", '!=', PayoutMember::class);
    }
}
