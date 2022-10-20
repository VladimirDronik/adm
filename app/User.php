<?php

namespace App;

use App\Models\UserPermission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type Тип: superadmin, admin, user
 * @property-read mixed $is_admin
 * @property-read mixed $is_super_admin
 * @property-read mixed $is_user
 * @property-read mixed $rus_type
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read int|null $notifications_count
 */
class User extends Authenticatable
{
    use Notifiable;

    const TYPE_USER = 'user';
    const TYPE_ADMIN = 'admin';
    const TYPE_SUPERADMIN = 'superadmin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_USER => 'Пользователь',
            self::TYPE_ADMIN => 'Администратор',
            self::TYPE_SUPERADMIN => 'Суперадминистратор'
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }

    public function getIsAdminAttribute()
    {
        return $this->type === self::TYPE_ADMIN;
    }

    public function getIsUserAttribute()
    {
        return $this->type === self::TYPE_USER;
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->type === self::TYPE_SUPERADMIN;
    }

    public function hasAccess(string $slug): bool
    {
        return UserPermission::hasAccess($this->type, $slug);
    }
}
