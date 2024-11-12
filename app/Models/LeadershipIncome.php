<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\LeadershipIncome
 *
 * @property int $id
 * @property int $member_id
 * @property int $from_member_id
 * @property int $staking_binary_match_id
 * @property string $binary_amount
 * @property string $percentage
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $fromMember
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\StakingBinaryMatch $stakingBinaryMatch
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereBinaryAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereFromMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereStakingBinaryMatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadershipIncome whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LeadershipIncome extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function walletTransaction(): MorphOne
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function fromMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'from_member_id', 'id');
    }

    public function stakingBinaryMatch(): BelongsTo
    {
        return $this->belongsTo(StakingBinaryMatch::class);
    }
}
