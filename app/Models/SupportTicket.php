<?php

namespace App\Models;

use App\Presenters\SupportTicketPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\SupportTicket
 *
 * @property int $id
 * @property string|null $ticket_id
 * @property int $member_id
 * @property string $title
 * @property int $status 1: Open, 2: Close
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SupportTicketMessage> $message
 * @property-read int|null $message_count
 *
 * @method static Builder|SupportTicket fromDate(string $date)
 * @method static Builder|SupportTicket newModelQuery()
 * @method static Builder|SupportTicket newQuery()
 * @method static Builder|SupportTicket open()
 * @method static Builder|SupportTicket query()
 * @method static Builder|SupportTicket toDate(string $date)
 * @method static Builder|SupportTicket whereCreatedAt($value)
 * @method static Builder|SupportTicket whereId($value)
 * @method static Builder|SupportTicket whereMemberId($value)
 * @method static Builder|SupportTicket whereStatus($value)
 * @method static Builder|SupportTicket whereTicketId($value)
 * @method static Builder|SupportTicket whereTitle($value)
 * @method static Builder|SupportTicket whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class SupportTicket extends Model
{
    use PresentableTrait;

    const STATUS_OPEN = 1;

    const STATUS_CLOSE = 2;

    const STATUSES = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_CLOSE => 'Closed',
    ];

    protected $presenter = SupportTicketPresenter::class;

    protected $guarded = [];

    public function message()
    {
        return $this->hasMany(SupportTicketMessage::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function isOpen(): bool
    {
        return $this->status == self::STATUS_OPEN;
    }

    public function isClose(): bool
    {
        return $this->status == self::STATUS_CLOSE;
    }

    /**
     * @return Builder
     */
    public function scopeOpen(Builder $query)
    {
        return $query->where("{$this->getTable()}.status", self::STATUS_OPEN);
    }

    public function scopeFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    public function scopeToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }
}
