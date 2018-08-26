<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    /**
     * @var string
     */
    protected $table = 'audit';

    /**
     * @return mixed
     */
    public function details()
    {
        return $this->hasMany(AuditDetail::class);
    }
}
