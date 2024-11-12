<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\DirectWalletIncome
 *
 * @property int $id
 * @property int $member_id
 * @property int $from_member_id
 * @property int $top_up_id
 * @property string $package_amount
 * @property string $percentage
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $fromMember
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\TopUp $topUp
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome query()
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome whereFromMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome wherePackageAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome whereTopUpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DirectWalletIncome whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DirectWalletIncome extends Model
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

    public function topUp(): BelongsTo
    {
        return $this->belongsTo(TopUp::class);
    }
}
