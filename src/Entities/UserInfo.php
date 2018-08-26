<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'section', 'value', 'user_id',
    ];

    /**
     * @var string
     */
    protected $table = 'user_info';
}
