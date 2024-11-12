<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\StakingIncome
 *
 * @property int $id
 * @property int $member_id
 * @property int $stake_coin_id
 * @property string $stack_amount
 * @property string $percentage
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\TopUp|null $topUp
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome query()
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome whereStackAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome whereStakeCoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingIncome whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class StakingIncome extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function walletTransaction(): MorphOne|WalletTransaction
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function topUp(): BelongsTo
    {
        return $this->belongsTo(TopUp::class);
    }
}
