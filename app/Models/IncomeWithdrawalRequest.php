<?php

namespace App\Models;

use App\Presenters\IncomeWithdrawalPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\IncomeWithdrawalRequest
 *
 * @property int $id
 * @property int $member_id
 * @property int|null $admin_id
 * @property int|null $company_wallet_id
 * @property string|null $from_address
 * @property string $to_address
 * @property string $amount
 * @property string $service_charge
 * @property string $total
 * @property string $remark
 * @property string|null $tx_hash
 * @property array|null $receipt
 * @property string|null $error
 * @property int $status 1:pending,2:approve,3:reject,4:Processing
 * @property int $blockchain_status 1:In-checkout,2:pending,3:success,4:fail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin|null $admin
 * @property-read \App\Models\CompanyWallet|null $companyWallet
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\WalletTransaction|null $walletTransactions
 *
 * @method static Builder|IncomeWithdrawalRequest createdAtFrom(string $date)
 * @method static Builder|IncomeWithdrawalRequest createdAtTo(string $date)
 * @method static Builder|IncomeWithdrawalRequest maxAmount($max)
 * @method static Builder|IncomeWithdrawalRequest maxCoin($max)
 * @method static Builder|IncomeWithdrawalRequest maxCoinPrice($max)
 * @method static Builder|IncomeWithdrawalRequest maxServiceCharge($max)
 * @method static Builder|IncomeWithdrawalRequest maxTotal($max)
 * @method static Builder|IncomeWithdrawalRequest minAmount($min)
 * @method static Builder|IncomeWithdrawalRequest minCoin($min)
 * @method static Builder|IncomeWithdrawalRequest minCoinPrice($min)
 * @method static Builder|IncomeWithdrawalRequest minServiceCharge($min)
 * @method static Builder|IncomeWithdrawalRequest minTotal($min)
 * @method static Builder|IncomeWithdrawalRequest newModelQuery()
 * @method static Builder|IncomeWithdrawalRequest newQuery()
 * @method static Builder|IncomeWithdrawalRequest query()
 * @method static Builder|IncomeWithdrawalRequest whereAdminId($value)
 * @method static Builder|IncomeWithdrawalRequest whereAmount($value)
 * @method static Builder|IncomeWithdrawalRequest whereBlockchainStatus($value)
 * @method static Builder|IncomeWithdrawalRequest whereCompanyWalletId($value)
 * @method static Builder|IncomeWithdrawalRequest whereCreatedAt($value)
 * @method static Builder|IncomeWithdrawalRequest whereError($value)
 * @method static Builder|IncomeWithdrawalRequest whereFromAddress($value)
 * @method static Builder|IncomeWithdrawalRequest whereId($value)
 * @method static Builder|IncomeWithdrawalRequest whereMemberId($value)
 * @method static Builder|IncomeWithdrawalRequest whereReceipt($value)
 * @method static Builder|IncomeWithdrawalRequest whereRemark($value)
 * @method static Builder|IncomeWithdrawalRequest whereServiceCharge($value)
 * @method static Builder|IncomeWithdrawalRequest whereStatus($value)
 * @method static Builder|IncomeWithdrawalRequest whereToAddress($value)
 * @method static Builder|IncomeWithdrawalRequest whereTotal($value)
 * @method static Builder|IncomeWithdrawalRequest whereTxHash($value)
 * @method static Builder|IncomeWithdrawalRequest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class IncomeWithdrawalRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['receipt' => 'array', 'status' => 'int', 'blockchain_status' => 'int'];

    use PresentableTrait;

    protected $presenter = IncomeWithdrawalPresenter::class;

    const STATUS_PENDING = 1;

    const STATUS_PROCESSING = 2; //4

    const STATUS_COMPLETED = 3; //5

    const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_COMPLETED => 'Completed',
    ];

    const BLOCKCHAIN_STATUS_PENDING = 1;

    const BLOCKCHAIN_STATUS_PROCESSING = 2;

    const BLOCKCHAIN_STATUS_VERIFYING = 3;

    const BLOCKCHAIN_STATUS_SUCCESS = 4;

    const BLOCKCHAIN_STATUS_FAILED = 5;

    const BLOCKCHAIN_STATUSES = [
        self::BLOCKCHAIN_STATUS_PENDING => 'Pending',
        self::BLOCKCHAIN_STATUS_PROCESSING => 'Processing',
        self::BLOCKCHAIN_STATUS_VERIFYING => 'Verifying',
        self::BLOCKCHAIN_STATUS_SUCCESS => 'Success',
        self::BLOCKCHAIN_STATUS_FAILED => 'Failed',
    ];

    const NETWORK_BSC = 'Binance';

    const MIN_GAS_BALANCE = 0.01;

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function walletTransactions(): MorphOne|WalletTransaction
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }

    public function companyWallet(): BelongsTo|CompanyWallet
    {
        return $this->belongsTo(CompanyWallet::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    //    public function isApproved(): bool
    //    {
    //        return $this->status === self::STATUS_APPROVED;
    //    }

    //    public function isRejected(): bool
    //    {
    //        return $this->status === self::STATUS_REJECTED;
    //    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function statusBtnClass(): string
    {
        if ($this->status === self::STATUS_PENDING) {
            return 'warning';
        }
        if ($this->status === self::STATUS_APPROVED) {
            return 'success';
        }
        if ($this->status === self::STATUS_REJECTED) {
            return 'danger';
        }
        if ($this->status === self::STATUS_PROCESSING) {
            return 'info';
        }
        if ($this->status === self::STATUS_COMPLETED) {
            return 'info';
        }
    }

    public function isBlockchainStatusPending(): bool
    {
        return $this->blockchain_status === self::BLOCKCHAIN_STATUS_PENDING;
    }

    public function isBlockchainStatusProcessing(): bool
    {
        return $this->blockchain_status === self::BLOCKCHAIN_STATUS_PROCESSING;
    }

    public function isBlockchainStatusVerifying(): bool
    {
        return $this->blockchain_status === self::BLOCKCHAIN_STATUS_VERIFYING;
    }

    public function isBlockchainStatusSuccess(): bool
    {
        return $this->blockchain_status === self::BLOCKCHAIN_STATUS_SUCCESS;
    }

    public function isBlockchainStatusFailed(): bool
    {
        return $this->blockchain_status === self::BLOCKCHAIN_STATUS_FAILED;
    }

    public function shortTxHash(): string
    {
        return $this->tx_hash
            ? substr($this->tx_hash, 0, 5).
            '...'
            .substr($this->tx_hash, strlen($this->tx_hash) - 4)
            : '';
    }

    public function shortFromWalletAddress(): string
    {
        return $this->from_address
            ? substr($this->from_address, 0, 5).
            '...'.
            substr($this->from_address, strlen($this->from_address) - 4)
            : '';
    }

    public function shortToWalletAddress(): string
    {
        return $this->to_address
            ? substr($this->to_address, 0, 5).
            '...'.
            substr($this->to_address, strlen($this->to_address) - 4)
            : '';
    }

    public function scopeCreatedAtFrom(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeCreatedAtTo(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }

    /**
     * @return Builder
     */
    public function scopeMaxAmount(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.amount", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinAmount(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.amount", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxCoinPrice(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.coin_price", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinCoinPrice(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.coin_price", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxCoin(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.coin", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinCoin(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.coin", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxServiceCharge(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.service_charge", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinServiceCharge(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.service_charge", '>=', $min);
    }

    /**
     * @return Builder
     */
    public function scopeMaxTotal(Builder $query, $max)
    {
        return $query->where("{$this->getTable()}.total", '<=', $max);
    }

    /**
     * @return Builder
     */
    public function scopeMinTotal(Builder $query, $min)
    {
        return $query->where("{$this->getTable()}.total", '>=', $min);
    }
}
