<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'section', 'value',
    ];

    /**
     * @var string
     */
    protected $table = 'setting';
}
