<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MemberLoginLog
 *
 * @property int $id
 * @property int $member_id
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 *
 * @method static \Database\Factories\MemberLoginLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberLoginLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MemberLoginLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function member(): BelongsTo|Member
    {
        return $this->belongsTo(Member::class);
    }
}
