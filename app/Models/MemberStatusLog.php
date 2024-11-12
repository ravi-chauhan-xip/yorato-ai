<?php

namespace App\Models;

use App\Presenters\MemberStatusLogPresenter;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\MemberStatusLog
 *
 * @property int $id
 * @property int|null $admin_user_id
 * @property int $member_id
 * @property int $last_status
 * @property int $new_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\User|null $user
 *
 * @method static Builder|MemberStatusLog joiningFromDate(string $date)
 * @method static Builder|MemberStatusLog joiningToDate(string $date)
 * @method static Builder|MemberStatusLog newModelQuery()
 * @method static Builder|MemberStatusLog newQuery()
 * @method static Builder|MemberStatusLog query()
 * @method static Builder|MemberStatusLog whereAdminUserId($value)
 * @method static Builder|MemberStatusLog whereCreatedAt($value)
 * @method static Builder|MemberStatusLog whereId($value)
 * @method static Builder|MemberStatusLog whereLastStatus($value)
 * @method static Builder|MemberStatusLog whereMemberId($value)
 * @method static Builder|MemberStatusLog whereNewStatus($value)
 * @method static Builder|MemberStatusLog whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class MemberStatusLog extends Model
{
    protected $guarded = [];

    use PresentableTrait;

    protected $presenter = MemberStatusLogPresenter::class;

    public const STATUS_FREE_MEMBER = 1;

    public const STATUS_ACTIVE = 2;

    public const STATUS_BLOCKED = 3;

    const STATUSES = [
        self::STATUS_FREE_MEMBER => 'Free',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BLOCKED => 'Blocked',
    ];

    /**
     * @return BelongsTo|Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'admin_user_id', 'id');
    }

    public function scopeJoiningFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeJoiningToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }

    /**
     * @return bool
     */
    public function isLastBlocked()
    {
        return $this->last_status == self::STATUS_BLOCKED;
    }

    /**
     * @return bool
     */
    public function isLastFree()
    {
        return $this->last_status == self::STATUS_FREE_MEMBER;
    }

    /**
     * @return bool
     */
    public function isLastActive()
    {
        return $this->last_status == self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isNewBlocked()
    {
        return $this->new_status == self::STATUS_BLOCKED;
    }

    /**
     * @return bool
     */
    public function isNewFree()
    {
        return $this->new_status == self::STATUS_FREE_MEMBER;
    }

    /**
     * @return bool
     */
    public function isNewActive()
    {
        return $this->new_status == self::STATUS_ACTIVE;
    }
}
