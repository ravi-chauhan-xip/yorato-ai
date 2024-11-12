<?php

namespace App\Models;

use App\Presenters\ExportPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\Export
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $admin_id
 * @property string $list_builder
 * @property array $request_input
 * @property array $extras
 * @property string $file_name
 * @property string $file_path
 * @property int $status
 * @property string|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|Export expired()
 * @method static \Database\Factories\ExportFactory factory($count = null, $state = [])
 * @method static Builder|Export fromDate(string $date)
 * @method static Builder|Export newModelQuery()
 * @method static Builder|Export newQuery()
 * @method static Builder|Export query()
 * @method static Builder|Export toDate(string $date)
 * @method static Builder|Export whereAdminId($value)
 * @method static Builder|Export whereCompletedAt($value)
 * @method static Builder|Export whereCreatedAt($value)
 * @method static Builder|Export whereExtras($value)
 * @method static Builder|Export whereFileName($value)
 * @method static Builder|Export whereFilePath($value)
 * @method static Builder|Export whereId($value)
 * @method static Builder|Export whereListBuilder($value)
 * @method static Builder|Export whereRequestInput($value)
 * @method static Builder|Export whereStatus($value)
 * @method static Builder|Export whereUpdatedAt($value)
 * @method static Builder|Export whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Export extends Model
{
    use HasFactory;
    use PresentableTrait;

    const STATUS_PENDING = 1;

    const STATUS_COMPLETED = 2;

    const STATUS_FAILED = 3;

    const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_FAILED => 'Failed',
    ];

    protected $guarded = ['id'];

    protected $casts = ['request_input' => 'array', 'extras' => 'array'];

    protected string $presenter = ExportPresenter::class;

    public function isPending(): bool
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status == self::STATUS_FAILED;
    }

    public function scopeFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            return $query->where('completed_at', '<=', now()->subDay()->startOfDay())
                ->orWhere(function (Builder $query) {
                    return $query->whereNull('completed_at')
                        ->where('created_at', '<=', now()->subDay()->startOfDay());
                });
        });
    }
}
