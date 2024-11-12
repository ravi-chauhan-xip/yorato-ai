<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\State
 *
 * @property int $id
 * @property int|null $country_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\City> $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Country|null $country
 *
 * @method static Builder|State newModelQuery()
 * @method static Builder|State newQuery()
 * @method static Builder|State query()
 * @method static Builder|State whereCountryId($value)
 * @method static Builder|State whereCreatedAt($value)
 * @method static Builder|State whereId($value)
 * @method static Builder|State whereName($value)
 * @method static Builder|State whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class State extends Model
{
    protected $guarded = ['id'];

    public function cities(): HasMany|City
    {
        return $this->hasMany(City::class);
    }

    public function country(): BelongsTo|Country
    {
        return $this->belongsTo(Country::class);
    }
}
