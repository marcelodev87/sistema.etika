<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientSubscriptionTaskComment extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clientTask()
    {
        return $this->belongsTo(ClientSubscriptionTask::class, 'cst_id');
    }
}
