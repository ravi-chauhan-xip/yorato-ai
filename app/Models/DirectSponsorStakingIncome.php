<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\DirectSponsorStakingIncome
 *
 * @property int $id
 * @property int $member_id
 * @property int $from_member_id
 * @property int $stake_coin_id
 * @property string $stake_amount
 * @property string $percentage
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $fromMember
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\StakeCoin|null $stackCoin
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome query()
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereFromMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereStakeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereStakeCoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectSponsorStakingIncome whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DirectSponsorStakingIncome extends Model
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

    public function stackCoin(): BelongsTo
    {
        return $this->belongsTo(StakeCoin::class);
    }
}
