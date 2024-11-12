<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserDeposit
 *
 * @property int $id
 * @property int $member_id
 * @property int $package_id
 * @property int $order_no
 * @property string $amount
 * @property string|null $block_no
 * @property string $from_address
 * @property string $to_address
 * @property string $transaction_hash
 * @property int $blockchain_status 1 = pending | 2 = completed | 3 = failed
 * @property string|null $remark
 * @property array|null $receipt
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\StakeCoin|null $stake
 * @property-read \App\Models\TopUp|null $topUp
 * @property-read \App\Models\WalletTransaction|null $walletTransaction
 *
 * @method static Builder|UserDeposit newModelQuery()
 * @method static Builder|UserDeposit newQuery()
 * @method static Builder|UserDeposit query()
 * @method static Builder|UserDeposit whereAmount($value)
 * @method static Builder|UserDeposit whereBlockNo($value)
 * @method static Builder|UserDeposit whereBlockchainStatus($value)
 * @method static Builder|UserDeposit whereCreatedAt($value)
 * @method static Builder|UserDeposit whereFromAddress($value)
 * @method static Builder|UserDeposit whereId($value)
 * @method static Builder|UserDeposit whereMemberId($value)
 * @method static Builder|UserDeposit whereOrderNo($value)
 * @method static Builder|UserDeposit wherePackageId($value)
 * @method static Builder|UserDeposit whereReceipt($value)
 * @method static Builder|UserDeposit whereRemark($value)
 * @method static Builder|UserDeposit whereToAddress($value)
 * @method static Builder|UserDeposit whereTransactionHash($value)
 * @method static Builder|UserDeposit whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class UserDeposit extends Model
{
    use HasFactory;

    protected $casts = ['receipt' => 'json'];

    const BLOCKCHAIN_STATUS_PENDING = 1;

    const BLOCKCHAIN_STATUS_COMPLETED = 2;

    const BLOCKCHAIN_STATUS_FAILED = 3;

    const BLOCKCHAIN_STATUSES = [
        self::BLOCKCHAIN_STATUS_PENDING => 'Pending',
        self::BLOCKCHAIN_STATUS_COMPLETED => 'Completed',
        self::BLOCKCHAIN_STATUS_FAILED => 'Failed',
    ];

    protected $guarded = ['id'];

    public static function generateRandomOrderNo($length = 8): int
    {
        $min = intval(str_repeat('1', $length));

        do {
            $max = intval(str_repeat('9', $length));
            $orderNo = mt_rand($min, $max);
            $length++;
        } while (self::whereOrderNo($orderNo)->exists());

        return $orderNo;
    }

    public function walletTransaction(): MorphOne
    {
        return $this->morphOne(WalletTransaction::class, 'responsible');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function topUp(): HasOne
    {
        return $this->hasOne(TopUp::class);
    }

    public function stake(): HasOne
    {
        return $this->hasOne(StakeCoin::class);
    }
}
