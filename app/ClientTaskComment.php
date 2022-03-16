<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTaskComment extends Model
{
    protected $guarded = [];

    public function clientTask()
    {
        return $this->belongsTo(ClientTask::class);
    }


}
