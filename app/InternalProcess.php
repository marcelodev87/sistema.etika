<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternalProcess extends Model
{
    protected $guarded = [];

    public function tasks()
    {
        return $this->belongsToMany(InternalTask::class, 'process_task',  'internal_process_id', 'internal_task_id')->withPivot('position');
    }
}
