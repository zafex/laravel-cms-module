<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    /**
     * @var string
     */
    protected $table = 'workflow_step';

    /**
     * @return mixed
     */
    public function verificators()
    {
        return $this->hasMany(WorkflowVerificator::class);
    }
}
