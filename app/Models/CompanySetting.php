<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\CompanySetting
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanySetting whereValue($value)
 *
 * @mixin \Eloquent
 */
class CompanySetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const WELCOME_LETTER_LOGO = 'welcome_letter_logo';

    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::WELCOME_LETTER_LOGO)
            ->singleFile();
    }
}
