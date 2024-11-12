<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AdminBVLog
 *
 * @property int $id
 * @property int $admin_id
 * @property int $member_id
 * @property int $parent_side
 * @property string $bv
 * @property int $type 1:Topup , 2: Stake
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereParentSide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminBVLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AdminBVLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const TYPE_TOP_UP = 1;

    public const TYPE_STAKE = 2;

    const TYPES = [
        self::TYPE_TOP_UP => 'TopUp',
        self::TYPE_STAKE => 'Stake',
    ];

    public function member(): BelongsTo|Member
    {
        return $this->belongsTo(Member::class);
    }
}
