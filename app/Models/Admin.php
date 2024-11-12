<?php

namespace App\Models;

use App\Presenters\AdminsPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Admin
 *
 * @property int $id
 * @property int|null $creator_id
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property string $password
 * @property string|null $remember_token
 * @property bool $is_super
 * @property int $status 1: Active, 2: In-Active
 * @property int $is_dark_theme
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminLoginLog> $adminLoginLogs
 * @property-read int|null $admin_login_logs_count
 * @property-read Admin|null $creatorId
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereIsDarkTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereIsSuper($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use PresentableTrait;

    protected $presenter = AdminsPresenter::class;

    const STATUS_ACTIVE = 1;

    const STATUS_IN_ACTIVE = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_IN_ACTIVE => 'In-Active',
    ];

    protected $guarded = ['id'];

    protected $casts = ['is_super' => 'bool'];

    public function isActive(): int
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isInActive(): int
    {
        return $this->status == self::STATUS_IN_ACTIVE;
    }

    public function isDarkTheme()
    {
        return $this->is_dark_theme;
    }

    public function creatorId(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'creator_id');
    }

    public function adminLoginLogs(): HasMany
    {
        return $this->hasMany(AdminLoginLog::class);
    }
}
