<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MemberStat
 *
 * @property int $id
 * @property int $member_id
 * @property string $staking_income
 * @property string $direct_income
 * @property string $direct_sponsor_staking
 * @property string $team_matching
 * @property string $team_matching_staking
 * @property string $leadership_bonus
 * @property string $admin_credit
 * @property string $all_income
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 *
 * @method static \Database\Factories\MemberStatFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereAdminCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereAllIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereDirectIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereDirectSponsorStaking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereLeadershipBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereStakingIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereTeamMatching($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereTeamMatchingStaking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberStat whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MemberStat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function member(): BelongsTo|Member
    {
        return $this->belongsTo(Member::class);
    }
}
