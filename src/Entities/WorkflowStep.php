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
    public function master()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, WorkflowVerificator::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function verificators()
    {
        return $this->hasMany(WorkflowVerificator::class);
    }
}
