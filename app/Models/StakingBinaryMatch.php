<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\StakingBinaryMatch
 *
 * @property int $id
 * @property int $member_id
 * @property string $amount
 * @property string $admin_charge
 * @property string $tds
 * @property string $total
 * @property int $capping_reached
 * @property string $left_members
 * @property string $right_members
 * @property string $left_total_bv
 * @property string $right_total_bv
 * @property string $left_forward_bv
 * @property string $right_forward_bv
 * @property string $left_new_bv
 * @property string $right_new_bv
 * @property string $left_completed_bv
 * @property string $right_completed_bv
 * @property int $is_show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereAdminCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereCappingReached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereIsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereLeftCompletedBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereLeftForwardBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereLeftMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereLeftNewBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereLeftTotalBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereRightCompletedBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereRightForwardBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereRightMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereRightNewBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereRightTotalBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereTds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakingBinaryMatch whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class StakingBinaryMatch extends Model
{
    protected $guarded = [];

    public function walletTransaction()
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }

    public function member(): BelongsTo|Member
    {
        return $this->belongsTo(Member::class);
    }
}
