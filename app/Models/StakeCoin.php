<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\StakeCoin
 *
 * @property int $id
 * @property int $member_id
 * @property int|null $from_member_id
 * @property int|null $user_deposit_id
 * @property int $package_id
 * @property string $amount
 * @property int $capping_days
 * @property int $remaining_days
 * @property int $status 1 = pending | 2 = Finish
 * @property int|null $done_by 1 = admin | 2 = member | 3 = web3
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member|null $fromMember
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\Package $package
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StakingIncome> $stakingIncomes
 * @property-read int|null $staking_incomes_count
 * @property-read \App\Models\UserDeposit|null $userDeposit
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static Builder|StakeCoin newModelQuery()
 * @method static Builder|StakeCoin newQuery()
 * @method static Builder|StakeCoin query()
 * @method static Builder|StakeCoin whereAmount($value)
 * @method static Builder|StakeCoin whereCappingDays($value)
 * @method static Builder|StakeCoin whereCreatedAt($value)
 * @method static Builder|StakeCoin whereDoneBy($value)
 * @method static Builder|StakeCoin whereFromMemberId($value)
 * @method static Builder|StakeCoin whereId($value)
 * @method static Builder|StakeCoin whereMemberId($value)
 * @method static Builder|StakeCoin wherePackageId($value)
 * @method static Builder|StakeCoin whereRemainingDays($value)
 * @method static Builder|StakeCoin whereStatus($value)
 * @method static Builder|StakeCoin whereUpdatedAt($value)
 * @method static Builder|StakeCoin whereUserDepositId($value)
 *
 * @mixin Eloquent
 */
class StakeCoin extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['lifetime_started_at' => 'datetime', 'is_register' => 'bool'];

    const DONE_BY_ADMIN = 1;

    const DONE_BY_MEMBER = 2;

    const DONE_BY_WEB3 = 3;

    const STATUS_PENDING = 1;

    const STATUS_ACTIVE = 2;

    const STATUS_FINISH = 3;

    const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_FINISH => 'Finish',
    ];

    const DONE_BYES = [
        self::DONE_BY_ADMIN => 'Admin',
        self::DONE_BY_MEMBER => 'Wallet',
        self::DONE_BY_WEB3 => 'Web3',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function fromMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'from_member_id', 'id');
    }

    public function userDeposit(): BelongsTo
    {
        return $this->belongsTo(UserDeposit::class);
    }

    public function package(): BelongsTo|Package
    {
        return $this->belongsTo(Package::class);
    }

    public function walletTransaction(): MorphOne
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }

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
        return $this->status == self::STATUS_FINISH;
    }

    public function stakingIncomes()
    {
        return $this->hasMany(StakingIncome::class);
    }
}
