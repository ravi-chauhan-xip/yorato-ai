<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminLoginLog
 *
 * @property int $id
 * @property int $admin_id
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminLoginLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AdminLoginLog extends Model
{
    protected $guarded = ['id'];
}
