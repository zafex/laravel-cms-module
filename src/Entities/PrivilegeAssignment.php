<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class PrivilegeAssignment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'role_id', 'permission_id',
    ];

    /**
     * @var string
     */
    protected $table = 'privilege_assignment';

    /**
     * @return mixed
     */
    public function permission()
    {
        return $this->belongsTo(Privilege::class, 'permission_id');
    }

    /**
     * @return mixed
     */
    public function role()
    {
        return $this->belongsTo(Privilege::class, 'role_id');
    }
}
