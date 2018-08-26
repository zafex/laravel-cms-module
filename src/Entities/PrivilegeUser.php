<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class PrivilegeUser extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'role_id', 'user_id',
    ];

    /**
     * @var string
     */
    protected $table = 'privilege_user';
}
