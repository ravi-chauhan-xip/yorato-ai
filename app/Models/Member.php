<?php

namespace App\Models;

use App\Models\Relations\MemberRelations;
use App\Models\Scopes\MemberScopes;
use App\Presenters\MemberPresenter;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laracasts\Presenter\PresentableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Member
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $package_id
 * @property int|null $parent_id
 * @property int|null $sponsor_id
 * @property int|null $left_id
 * @property int|null $right_id
 * @property int|null $parent_side 1: Left, 2: Right
 * @property int $left_count
 * @property int $right_count
 * @property string $left_bv
 * @property string $right_bv
 * @property string $left_power
 * @property string $right_power
 * @property string $left_stake_bv
 * @property string $right_stake_bv
 * @property string $left_stake_power
 * @property string $right_stake_power
 * @property int $sponsored_left
 * @property int $sponsored_right
 * @property string|null $path
 * @property string|null $sponsor_path
 * @property int $level
 * @property int $sponsor_level
 * @property-read int|null $sponsored_count
 * @property string $wallet_balance
 * @property int $is_paid 1 :un paid, 2 :paid
 * @property int $status 1: Free Member, 2: Active, 3: Block
 * @property \Illuminate\Support\Carbon|null $activated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\BinaryMatch> $binaryMatches
 * @property-read int|null $binary_matches_count
 * @property-read Collection<int, Member> $children
 * @property-read int|null $children_count
 * @property-read Collection<int, \App\Models\UserDeposit> $deposits
 * @property-read int|null $deposits_count
 * @property-read Collection<int, \App\Models\IncomeWithdrawalRequest> $incomeWithdrawalRequests
 * @property-read int|null $income_withdrawal_requests_count
 * @property-read \App\Models\MemberLoginLog|null $lastLoginLog
 * @property-read Member|null $left
 * @property-read Collection<int, \App\Models\MemberLoginLog> $loginLogs
 * @property-read int|null $login_logs_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, \App\Models\MemberStatusLog> $memberStatusLog
 * @property-read int|null $member_status_log_count
 * @property-read \App\Models\Package|null $package
 * @property-read Member|null $parent
 * @property-read Member|null $right
 * @property-read Member|null $sponsor
 * @property-read Collection<int, Member> $sponsored
 * @property-read Collection<int, \App\Models\StakingBinaryMatch> $stakingBinaryMatches
 * @property-read int|null $staking_binary_matches_count
 * @property-read \App\Models\MemberStat|null $stat
 * @property-read Collection<int, \App\Models\SupportTicket> $supportTicket
 * @property-read int|null $support_ticket_count
 * @property-read \App\Models\User $user
 * @property-read Collection<int, \App\Models\WalletTransaction> $walletTransactions
 * @property-read int|null $wallet_transactions_count
 *
 * @method static Builder|Member activatedFromDate(string $date)
 * @method static Builder|Member activatedToDate(string $date)
 * @method static Builder|Member active()
 * @method static Builder|Member childrenOf(\App\Models\Member $parent)
 * @method static Builder|Member createdAtFrom(string $date)
 * @method static Builder|Member createdAtTo(string $date)
 * @method static Builder|Member eligibleForPayout()
 * @method static \Database\Factories\MemberFactory factory($count = null, $state = [])
 * @method static Builder|Member joiningFromDate(string $date)
 * @method static Builder|Member joiningToDate(string $date)
 * @method static Builder|Member maxBalance($max)
 * @method static Builder|Member maxLeftPv($max)
 * @method static Builder|Member maxRightPv($max)
 * @method static Builder|Member minBalance($min)
 * @method static Builder|Member minLeftPv($min)
 * @method static Builder|Member minRightPv($min)
 * @method static Builder|Member newModelQuery()
 * @method static Builder|Member newQuery()
 * @method static Builder|Member notActive()
 * @method static Builder|Member notBlocked()
 * @method static Builder|Member paid()
 * @method static Builder|Member query()
 * @method static Builder|Member whereActivatedAt($value)
 * @method static Builder|Member whereCreatedAt($value)
 * @method static Builder|Member whereId($value)
 * @method static Builder|Member whereIsPaid($value)
 * @method static Builder|Member whereLeftBv($value)
 * @method static Builder|Member whereLeftCount($value)
 * @method static Builder|Member whereLeftId($value)
 * @method static Builder|Member whereLeftPower($value)
 * @method static Builder|Member whereLeftStakeBv($value)
 * @method static Builder|Member whereLeftStakePower($value)
 * @method static Builder|Member whereLevel($value)
 * @method static Builder|Member wherePackageId($value)
 * @method static Builder|Member whereParentId($value)
 * @method static Builder|Member whereParentSide($value)
 * @method static Builder|Member wherePath($value)
 * @method static Builder|Member whereRightBv($value)
 * @method static Builder|Member whereRightCount($value)
 * @method static Builder|Member whereRightId($value)
 * @method static Builder|Member whereRightPower($value)
 * @method static Builder|Member whereRightStakeBv($value)
 * @method static Builder|Member whereRightStakePower($value)
 * @method static Builder|Member whereSponsorId($value)
 * @method static Builder|Member whereSponsorLevel($value)
 * @method static Builder|Member whereSponsorPath($value)
 * @method static Builder|Member whereSponsoredCount($value)
 * @method static Builder|Member whereSponsoredLeft($value)
 * @method static Builder|Member whereSponsoredRight($value)
 * @method static Builder|Member whereStatus($value)
 * @method static Builder|Member whereUpdatedAt($value)
 * @method static Builder|Member whereUserId($value)
 * @method static Builder|Member whereWalletBalance($value)
 *
 * @mixin Eloquent
 */
class Member extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use MemberRelations;
    use MemberScopes;
    use PresentableTrait;

    protected string $presenter = MemberPresenter::class;

    protected $guarded = [];

    protected $casts = ['activated_at' => 'datetime'];

    public const PARENT_SIDE_LEFT = 1;

    public const PARENT_SIDE_RIGHT = 2;

    const PARENT_SIDES = [
        self::PARENT_SIDE_LEFT => 'Left',
        self::PARENT_SIDE_RIGHT => 'Right',
    ];

    public const STATUS_FREE_MEMBER = 1;

    public const STATUS_ACTIVE = 2;

    public const STATUS_BLOCKED = 3;

    const STATUSES = [
        self::STATUS_FREE_MEMBER => 'Free',
        self::STATUS_ACTIVE => 'Active',
    ];

    public const IS_UN_PAID = 1;

    public const IS_PAID = 2;

    const IS_PAID_STATUSES = [
        self::IS_UN_PAID => 'Un-paid',
        self::IS_PAID => 'Paid',
    ];

    public const MC_INVOICE_PDF = 'invoice_pdf';

    public const MC_PROFILE_IMAGE = 'profile_image';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MC_PROFILE_IMAGE)
            ->singleFile();
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * @return Member|null
     */
    public function extremeRightMember()
    {
        $right = $this->right;

        if ($right && $right->right) {
            while ($right->right) {
                $right = $right->right;
            }
        }

        return $right;
    }

    /**
     * @return Member|null
     */
    public function extremeLeftMember()
    {
        $left = $this->left;

        if ($left && $left->left) {
            while ($left->left) {
                $left = $left->left;
            }
        }

        return $left;
    }

    /**
     * Get all parents for whom this member is on right or left as separate
     *
     * @return array
     */
    public function getSeparatedParents()
    {
        $leftParents = [];
        $rightParents = [];

        if ($parent = $this->parent) {
            $parents = self::latest('id')->findMany(explode('/', $parent->path));

            $member = $this;

            $parents->each(function (self $parent) use (&$leftParents, &$rightParents, &$member) {
                if ($member->parent_side == Member::PARENT_SIDE_LEFT) {
                    $leftParents[] = $parent;
                } elseif ($member->parent_side == Member::PARENT_SIDE_RIGHT) {
                    $rightParents[] = $parent;
                }

                $member = $parent;
            });
        }

        return [
            'left' => $leftParents,
            'right' => $rightParents,
        ];
    }

    public function getSeparatedChildren(): \Illuminate\Support\Collection
    {
        $leftChildren = collect();
        $rightChildren = collect();

        if ($this->left) {
            $leftChildren->push(clone $this->left);
            $leftChildren = $leftChildren->merge($this->left->getAllChildren());
        }

        if ($this->right) {
            $rightChildren->push(clone $this->right);
            $rightChildren = $rightChildren->merge($this->right->getAllChildren());
        }

        return collect([
            'left' => $leftChildren,
            'right' => $rightChildren,
        ]);
    }

    public function getAllChildren(?\Illuminate\Support\Collection &$children = null): ?\Illuminate\Support\Collection
    {
        if (! $children) {
            $children = collect();
        }

        if ($this->children) {
            foreach ($this->children as $child) {
                $children->push(clone $child);
                $child->getAllChildren($children);
            }
        }

        return $children;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->status == self::STATUS_BLOCKED;
    }

    /**
     * @return bool
     */
    public function isFree()
    {
        return $this->status == self::STATUS_FREE_MEMBER;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isPrimeMember(): bool
    {
        return $this->isActive();
    }

    /**
     * Check if member has reached daily capping
     *
     * @return bool
     */
    public function hasReachedCapping()
    {
        $dailyIncome = $this->binaryMatches()
            ->whereDate('created_at', today())
            ->sum('amount');

        return $dailyIncome >= $this->package->capping;
    }

    /**
     * @return array|null
     */
    public function getCashBackPackage()
    {
        $cashBackPackage = null;

        foreach (Setting::get('cashBackPackages') as $package) {
            if ($this->sponsored_left >= $package['step'] && $this->sponsored_right >= $package['step']) {
                $cashBackPackage = $package;
            }
        }

        return $cashBackPackage;
    }

    /**
     * @return bool
     */
    public function isChildOf(Member $parent)
    {
        return Str::contains($this->path, $parent->path);
    }

    public function calculateTransactionDetails(float $amount): object
    {
        $tds = ($amount * settings('tds_percent')) / 100;
        $adminCharge = ($amount * settings('admin_charge_percent')) / 100;

        $total = $amount - $tds - $adminCharge;

        return (object) [
            'amount' => $amount,
            'tds' => round($tds, 2),
            'adminCharge' => round($adminCharge, 2),
            'total' => round($total, 2),
        ];
    }

    public function toGenealogy(int $level = 3, ?Collection $children = null): static
    {
        if (! $children) {
            $children = Member::where('path', 'like', $this->path.'/%')
                ->where('level', '<=', $this->level + $level)
                ->with('user', 'media', 'package', 'left', 'right')
                ->get();
        }

        if ($level > 0) {
            foreach ($children as $child) {
                if ($this->left_id && $this->left_id == $child->id) {
                    $this->left = $child;
                    $this->left->toGenealogy($level - 1, $children);
                }

                if ($this->right_id && $this->right_id == $child->id) {
                    $this->right = $child;
                    $this->right->toGenealogy($level - 1, $children);
                }
            }
        }

        return $this;
    }

    public function scopeJoiningFromDate(Builder $query, string $date): BuilderCom
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeJoiningToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }

    public function scopeActivatedFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.activated_at", '>=', $date);
    }

    public function scopeActivatedToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.activated_at", '<=', $date);
    }

    public function calculateCapping($amount)
    {
        $activeStack = StakeCoin::where('member_id', $this->id)
            ->whereStatus(StakeCoin::STATUS_ACTIVE)
            ->where('remaining_capping', '>', 0)
            ->orderBy('id', 'desc')
            ->first();
        $comment = '';
        if ($activeStack) {
            if ($activeStack->remaining_capping >= $amount) {
                $income = $amount;
            } else {
                $extraCapping = $amount - $activeStack->remaining_capping;
                if (StakeCoin::where('member_id', $this->id)
                    ->whereStatus(StakeCoin::STATUS_ACTIVE)
                    ->where('remaining_capping', '>', $extraCapping)
                    ->orderBy('id', 'desc')
                    ->first()) {
                    $income = $amount;
                } else {
                    $income = $activeStack->remaining_capping;
                    $comment = 'partial capping reached for';
                }
            }
        } else {
            $income = 0;
            $comment = 'Capping reached for';
        }

        return ['income' => $income, 'comment' => $comment];
    }

    public function cappingRemaining()
    {
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $dailyIncome = StakingBinaryMatch::where('member_id', $this->id)
            ->whereCappingReached(false)
            ->whereBetween('created_at', [
                $startDate, $endDate,
            ])
            ->sum('amount');

        if ($this->package->capping) {
            return $dailyIncome < $this->package->capping
                ? ($this->package->capping - $dailyIncome)
                : 0;
        } else {
            return false;
        }
    }
}
