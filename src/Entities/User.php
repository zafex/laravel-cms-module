<?php

namespace Apiex\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'permissions',
    ];

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @return mixed
     */
    public function details()
    {
        return $this->hasMany(UserInfo::class);
    }

    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
        ];
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->hasManyThrough(
            PrivilegeAssignment::class,
            PrivilegeUser::class,
            'user_id', // key on role_user for user
            'role_id', // key on type role for role_user
            'id', // key on user for role user
            'role_id' // key on role_uer for type privilege
        );
    }

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Privilege::class);
    }
}
