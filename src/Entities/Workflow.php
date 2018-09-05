<?php

namespace Apiex\Entities;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    /**
     * @var string
     */
    protected $table = 'workflow';

    /**
     * @return mixed
     */
    public function steps()
    {
        return $this->hasMany(WorkflowStep::class);
    }

    /**
     * @return mixed
     */
    public function verificators()
    {
        return $this->hasManyThrough(WorkflowVerificator::class, WorkflowStep::class);
    }
}
