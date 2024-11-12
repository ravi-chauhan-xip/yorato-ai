<?php

namespace App\Models\Relations;

use App\Models\BinaryMatch;
use App\Models\IncomeWithdrawalRequest;
use App\Models\MemberLoginLog;
use App\Models\MemberStat;
use App\Models\MemberStatusLog;
use App\Models\Package;
use App\Models\StakingBinaryMatch;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserDeposit;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait MemberRelations
{
    public function user(): BelongsTo|User
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo|static
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany|static
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function incomeWithdrawalRequests(): HasMany|static
    {
        return $this->hasMany(IncomeWithdrawalRequest::class);
    }

    public function package(): BelongsTo|static
    {
        return $this->belongsTo(Package::class);
    }

    public function sponsor(): BelongsTo|static
    {
        return $this->belongsTo(self::class);
    }

    public function deposits(): HasMany|UserDeposit
    {
        return $this->hasMany(UserDeposit::class);
    }

    public function memberStatusLog(): MemberStatusLog|HasMany
    {
        return $this->hasMany(MemberStatusLog::class);
    }

    public function sponsored(): HasMany|static
    {
        return $this->hasMany(self::class, 'sponsor_id');
    }

    public function left(): HasOne|static
    {
        return $this->hasOne(self::class, 'id', 'left_id');
    }

    public function right(): HasOne|static
    {
        return $this->hasOne(self::class, 'id', 'right_id');
    }

    public function binaryMatches(): HasMany|BinaryMatch
    {
        return $this->hasMany(BinaryMatch::class);
    }

    public function stakingBinaryMatches(): HasMany|StakingBinaryMatch
    {
        return $this->hasMany(StakingBinaryMatch::class);
    }

    public function walletTransactions(): HasMany|WalletTransaction
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function supportTicket(): HasMany|SupportTicket
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function loginLogs(): HasMany|MemberLoginLog
    {
        return $this->hasMany(MemberLoginLog::class);
    }

    public function lastLoginLog(): HasOne|MemberLoginLog
    {
        return $this->hasOne(MemberLoginLog::class)
            ->latest();
    }

    public function stat(): HasOne|MemberStat
    {
        return $this->hasOne(MemberStat::class);
    }
}
