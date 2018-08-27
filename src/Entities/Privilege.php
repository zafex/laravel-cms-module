<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'section',
    ];

    /**
     * @var string
     */
    protected $table = 'privilege';

    /**
     * @return mixed
     */
    public function childRelations()
    {
        return $this->hasMany(PrivilegeAssignment::class, 'role_id', 'id');
    }
}
