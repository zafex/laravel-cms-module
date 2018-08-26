<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class PrivilegeRole extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'role_id', 'privilege_id',
    ];

    /**
     * @var string
     */
    protected $table = 'privilege_role';

    /**
     * @return mixed
     */
    public function privilege()
    {
        return $this->belongsTo(Privilege::class);
    }
}
