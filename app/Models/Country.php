<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string|null $regex
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 *
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country query()
 * @method static Builder|Country whereCode($value)
 * @method static Builder|Country whereCreatedAt($value)
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereName($value)
 * @method static Builder|Country whereRegex($value)
 * @method static Builder|Country whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Country extends Model
{
    use InteractsWithMedia;

    const MC_IMAGE = 'image';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MC_IMAGE)
            ->singleFile();
    }
}
