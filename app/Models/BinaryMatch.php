<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\BinaryMatch
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
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereAdminCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereCappingReached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereIsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereLeftCompletedBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereLeftForwardBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereLeftMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereLeftNewBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereLeftTotalBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereRightCompletedBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereRightForwardBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereRightMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereRightNewBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereRightTotalBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereTds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BinaryMatch whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class BinaryMatch extends Model
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
