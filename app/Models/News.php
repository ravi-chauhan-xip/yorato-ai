<?php

namespace App\Models;

use App\Presenters\NewPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\News
 *
 * @method static \Database\Factories\NewsFactory factory($count = null, $state = [])
 * @method static Builder|News fromDate(string $date)
 * @method static Builder|News newModelQuery()
 * @method static Builder|News newQuery()
 * @method static Builder|News query()
 * @method static Builder|News toDate(string $date)
 *
 * @mixin \Eloquent
 */
class News extends Model
{
    use HasFactory;
    use PresentableTrait;

    protected $guarded = [];

    protected $table = 'news';

    protected string $presenter = NewPresenter::class;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In-Active',
    ];

    public function scopeFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }
}
