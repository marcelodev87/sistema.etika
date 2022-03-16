<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ClientProcessTask extends Model
{
    protected $guarded = [];

    protected $dates = ['end_at', 'created_at'];

    public function task()
    {
        return $this->belongsTo(InternalTask::class, 'task_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_person');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function comments()
    {
        return $this->hasMany(ClientProcessTaskComment::class);
    }

    public function process()
    {
        return $this->belongsTo(ClientProcess::class, 'client_process_id');
    }

    public function isLate()
    {
        $now = Carbon::now();
        if($this->closed){
            if(!$this->end_at->gte($this->closed_at)){
                return true;
            }
        }else if ($now->gte($this->end_at)) {
            return true;
        }
        return false;
    }
}
