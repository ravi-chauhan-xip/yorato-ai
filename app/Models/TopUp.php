<?php

namespace App\Models;

use App\Presenters\TopUpPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\TopUp
 *
 * @property int $id
 * @property int|null $member_id
 * @property int|null $from_member_id
 * @property int|null $package_id
 * @property int|null $user_deposit_id
 * @property string $amount
 * @property int $status 1 = pending | 2 = success
 * @property int|null $done_by 1 = admin | 2 = member | 3 :web3
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member|null $fromMember
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\Package|null $package
 * @property-read \App\Models\Member|null $toppedUp
 * @property-read \App\Models\UserDeposit|null $userDeposit
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static Builder|TopUp active()
 * @method static Builder|TopUp createdAtFrom(string $date)
 * @method static Builder|TopUp createdAtTo(string $date)
 * @method static Builder|TopUp fromDate($fromDate)
 * @method static Builder|TopUp maxAmount($max)
 * @method static Builder|TopUp maxGstAmount($max)
 * @method static Builder|TopUp minAmount($min)
 * @method static Builder|TopUp minGstAmount($min)
 * @method static Builder|TopUp newModelQuery()
 * @method static Builder|TopUp newQuery()
 * @method static Builder|TopUp notExpired()
 * @method static Builder|TopUp query()
 * @method static Builder|TopUp toDate($toDate)
 * @method static Builder|TopUp whereAmount($value)
 * @method static Builder|TopUp whereCreatedAt($value)
 * @method static Builder|TopUp whereDoneBy($value)
 * @method static Builder|TopUp whereFromMemberId($value)
 * @method static Builder|TopUp whereId($value)
 * @method static Builder|TopUp whereMemberId($value)
 * @method static Builder|TopUp wherePackageId($value)
 * @method static Builder|TopUp whereStatus($value)
 * @method static Builder|TopUp whereUpdatedAt($value)
 * @method static Builder|TopUp whereUserDepositId($value)
 *
 * @mixin \Eloquent
 */
class TopUp extends Model
{
    use HasFactory;
    use PresentableTrait;

    protected $guarded = [];

    const DONE_BY_ADMIN = 1;

    const DONE_BY_MEMBER = 2;

    const DONE_BY_WEB3 = 3;

    const STATUS_PENDING = 1;

    const STATUS_SUCCESS = 2;

    const STATUS_FAIL = 3;

    const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_SUCCESS => 'Success',
        self::STATUS_FAIL => 'Fail',
    ];

    const DONE_BYES = [
        self::DONE_BY_ADMIN => 'Admin',
        self::DONE_BY_MEMBER => 'Wallet',
        self::DONE_BY_WEB3 => 'Web3',
    ];

    protected $presenter = TopUpPresenter::class;

    /**
     * @param  Builder|TopUp  $query
     * @return Builder|TopUp
     */
    public function scopeActive($query)
    {
        return $query->where("{$this->getTable()}.active", true);
    }

    /**
     * @param  Builder|TopUp  $query
     * @return Builder|TopUp
     */
    public function scopeNotExpired($query)
    {
        return $query->where("{$this->getTable()}.roi_completed_weeks", '<', 20);
    }

    /**
     * @return BelongsTo|Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function userDeposit()
    {
        return $this->belongsTo(UserDeposit::class);
    }

    /**
     * @return BelongsTo|Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
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
    public function scopeMinGstAmount(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.gst_amount", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxGstAmount(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.gst_amount", '<=', $max);
    }

    /**
     * @return BelongsTo
     */
    public function toppedUp()
    {
        return $this->belongsTo(Member::class, 'topped_up_by', 'id');
    }

    public function fromMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'from_member_id', 'id');
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeFromDate(Builder $query, $fromDate)
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $fromDate);
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeToDate(Builder $query, $toDate)
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $toDate);
    }

    public function walletTransaction(): MorphOne
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }
}
