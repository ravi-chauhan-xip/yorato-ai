<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int|null $city_id
 * @property string|null $name
 * @property string|null $email
 * @property string $wallet_address
 * @property int $status 1: Active , 2: In-active
 * @property bool $is_dark_theme
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\AdminLoginLog> $adminLoginLogs
 * @property-read int|null $admin_login_logs_count
 * @property-read \App\Models\City|null $city
 * @property-read Collection<int, \App\Models\Export> $exports
 * @property-read int|null $exports_count
 * @property-read \App\Models\Member|null $member
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\State|null $state
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereCityId($value)
 * @method static Builder|User whereCountryId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsDarkTheme($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereStateId($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereWalletAddress($value)
 *
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    const GENDER_MALE = 1;

    const GENDER_FEMALE = 2;

    const GENDER = [
        self::GENDER_MALE => 'Male',
        self::GENDER_FEMALE => 'Female',
    ];

    public const STATUS_ACTIVE = 1;

    public const STATUS_BLOCKED = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BLOCKED => 'Blocked',
    ];

    protected $guarded = [];

    protected $casts = ['is_dark_theme' => 'boolean', 'dob' => 'datetime'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function member(): HasOne|Member
    {
        return $this->hasOne(Member::class);
    }

    public function adminLoginLogs(): HasMany|AdminLoginLog
    {
        return $this->hasMany(AdminLoginLog::class);
    }

    public function state(): BelongsTo|State
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo|City
    {
        return $this->belongsTo(City::class);
    }

    public function exports(): HasMany|Export
    {
        return $this->hasMany(Export::class);
    }

    public function isDarkTheme(): bool
    {
        return $this->is_dark_theme;
    }

    /**
     * {@inheritDoc}
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
