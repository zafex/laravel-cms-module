<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class AuditDetail extends Model
{
    const CREATED_AT = null;

    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'audit_detail';
}
