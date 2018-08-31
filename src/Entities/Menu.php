<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * @var string
     */
    protected $table = 'menu';

    /**
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
