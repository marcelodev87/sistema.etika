<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternalTask extends Model
{
    protected $guarded = [];

    public function clientTask()
    {
        return $this->hasMany(ClientTask::class, 'task_id');
    }

    public function clientProcess()
    {
        return $this->hasMany(ClientProcessTask::class, 'task_id');
    }

    public function clientSubscription()
    {
        return $this->hasMany(ClientSubscriptionTask::class, 'task_id');
    }
}
