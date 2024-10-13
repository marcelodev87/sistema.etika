<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientProcessLog extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function process()
    {
        return $this->belongsTo(InternalProcess::class);
    }
}
