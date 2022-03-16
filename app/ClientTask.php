<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
    protected $guarded = [];
    protected $dates = ['end_at', 'closed_at'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function task()
    {
        return $this->belongsTo(InternalTask::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(ClientTaskComment::class, 'client_task_id');
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

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
