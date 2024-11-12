<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompanyWallet
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $private_key
 * @property string $bnb_balance
 * @property string $usdt_balance
 * @property string $coin_balance
 * @property int $status 1 = active | 2 = inactive
 * @property int $transaction_count
 * @property \Illuminate\Support\Carbon|null $locked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|CompanyWallet active()
 * @method static Builder|CompanyWallet locked()
 * @method static Builder|CompanyWallet newModelQuery()
 * @method static Builder|CompanyWallet newQuery()
 * @method static Builder|CompanyWallet query()
 * @method static Builder|CompanyWallet unLocked()
 * @method static Builder|CompanyWallet whereAddress($value)
 * @method static Builder|CompanyWallet whereBnbBalance($value)
 * @method static Builder|CompanyWallet whereCoinBalance($value)
 * @method static Builder|CompanyWallet whereCreatedAt($value)
 * @method static Builder|CompanyWallet whereId($value)
 * @method static Builder|CompanyWallet whereLockedAt($value)
 * @method static Builder|CompanyWallet whereName($value)
 * @method static Builder|CompanyWallet wherePrivateKey($value)
 * @method static Builder|CompanyWallet whereStatus($value)
 * @method static Builder|CompanyWallet whereTransactionCount($value)
 * @method static Builder|CompanyWallet whereUpdatedAt($value)
 * @method static Builder|CompanyWallet whereUsdtBalance($value)
 *
 * @mixin \Eloquent
 */
class CompanyWallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['locked_at' => 'datetime'];

    const STATUS_ACTIVE = 1;

    const STATUS_IN_ACTIVE = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_IN_ACTIVE => 'In-Active',
    ];

    public function scopeActive(Builder $query): Builder|CompanyWallet
    {
        return $query->where("$this->table.status", self::STATUS_ACTIVE);
    }

    public function scopeLocked(Builder $query): Builder|CompanyWallet
    {
        return $query->whereNotNull("$this->table.locked_at");
    }

    public function scopeUnLocked(Builder $query): Builder|CompanyWallet
    {
        return $query->whereNull("$this->table.locked_at");
    }

    public function lockWallet()
    {
        $this->refresh();

        if ($this->locked_at === null) {
            $this->update([
                'locked_at' => now(),
            ]);
        }
    }

    public function unLockWallet()
    {
        $this->refresh();

        if ($this->locked_at !== null) {
            $this->update([
                'locked_at' => null,
            ]);
        }
    }

    public function typeBtnClass(): string
    {
        if ($this->locked_at === null) {
            return 'success';
        }

        return 'danger';
    }

    public function statusBtnClass(): string
    {
        if ($this->status === self::STATUS_ACTIVE) {
            return 'success';
        }

        if ($this->status === self::STATUS_IN_ACTIVE) {
            return 'danger';
        }

        return 'primary';
    }

    public function shortAddress(): string
    {
        return substr($this->address, 0, 5).
            '...'.
            substr($this->address, strlen($this->address) - 4);
    }

    public function isLocked(): bool
    {
        return $this->locked_at !== null;
    }

    public function isUnlocked(): bool
    {
        return $this->locked_at === null;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
