<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\WebsitePopup
 *
 * @property int $id
 * @property string $name
 * @property int $status 1: Active, 2: In-Active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsitePopup whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class WebsitePopup extends Model implements HasMedia
{
    use InteractsWithMedia;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In-Active',
    ];

    const MEDIA_COLLECTION_IMAGE_WEBSITE_POPUP = 'website_popup';

    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_IMAGE_WEBSITE_POPUP)
            ->singleFile();
    }
}
