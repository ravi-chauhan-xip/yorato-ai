<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\BuyCoin
 *
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\WalletTransaction|null $walletTransactions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BuyCoin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BuyCoin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BuyCoin query()
 *
 * @mixin \Eloquent
 */
class BuyCoin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function member(): BelongsTo|Member
    {
        return $this->belongsTo(Member::class);
    }

    public function walletTransactions(): MorphOne|WalletTransaction
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }
}
