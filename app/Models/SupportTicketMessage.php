<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\SupportTicketMessage
 *
 * @property int $id
 * @property int $support_ticket_id
 * @property int $messageable_id
 * @property string $messageable_type
 * @property string $body
 * @property int $is_read 0: Un-Read, 1: Read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin|null $admin
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\SupportTicket $supportTicket
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereMessageableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereMessageableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereSupportTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class SupportTicketMessage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    const MC_IMAGE = 'image';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MC_IMAGE)
            ->singleFile();
    }

    public function byAdmin()
    {
        return $this->messageable_type == Admin::class;
    }

    public function byMember()
    {
        return $this->messageable_type == Member::class;
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'messageable_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'messageable_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class);
    }
}
