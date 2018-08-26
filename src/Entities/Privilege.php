<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    /**
     * @var string
     */
    protected $table = 'privilege';
}
