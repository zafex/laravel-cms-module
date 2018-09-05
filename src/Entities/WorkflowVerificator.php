<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class WorkflowVerificator extends Model
{
    /**
     * @var string
     */
    protected $table = 'workflow_verificator';

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
