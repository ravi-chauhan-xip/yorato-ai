<?php

namespace App\Models;

use App\Presenters\PackagePresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\Package
 *
 * @property int $id
 * @property string $name
 * @property string $amount
 * @property string $staking_min
 * @property string $staking_max
 * @property string|null $capping
 * @property int $status 1: Active, 2: In-Active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|Package active()
 * @method static Builder|Package fromDate(string $date)
 * @method static Builder|Package maxAmount($max)
 * @method static Builder|Package minAmount($min)
 * @method static Builder|Package newModelQuery()
 * @method static Builder|Package newQuery()
 * @method static Builder|Package query()
 * @method static Builder|Package toDate(string $date)
 * @method static Builder|Package whereAmount($value)
 * @method static Builder|Package whereCapping($value)
 * @method static Builder|Package whereCreatedAt($value)
 * @method static Builder|Package whereId($value)
 * @method static Builder|Package whereName($value)
 * @method static Builder|Package whereStakingMax($value)
 * @method static Builder|Package whereStakingMin($value)
 * @method static Builder|Package whereStatus($value)
 * @method static Builder|Package whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Package extends Model
{
    use PresentableTrait;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In-Active',
    ];

    const TYPE_WORKING = 1;

    const TYPE_NON_WORKING = 2;

    protected $guarded = [];

    protected $presenter = PackagePresenter::class;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isInActive()
    {
        return $this->status == self::STATUS_INACTIVE;
    }

    /**
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where("{$this->getTable()}.status", self::STATUS_ACTIVE);
    }

    public function scopeFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }

    public function scopeMinAmount(Builder $query, $min): Builder
    {
        return $query->where("{$this->getTable()}.amount", '>=', $min);
    }

    public function scopeMaxAmount(Builder $query, $max): Builder
    {
        return $query->where("{$this->getTable()}.amount", '<=', $max);
    }
}
