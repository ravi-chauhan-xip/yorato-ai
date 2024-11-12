<?php

namespace App\Models;

use App\Presenters\FAQPresenter;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\Faq
 *
 * @method static \Database\Factories\FaqFactory factory($count = null, $state = [])
 * @method static Builder|Faq fromDate(string $date)
 * @method static Builder|Faq newModelQuery()
 * @method static Builder|Faq newQuery()
 * @method static Builder|Faq query()
 * @method static Builder|Faq toDate(string $date)
 *
 * @mixin Eloquent
 */
class Faq extends Model
{
    use HasFactory;
    use PresentableTrait;

    protected $guarded = [];

    protected string $presenter = FAQPresenter::class;

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
