<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientProcessTaskComment extends Model
{
    protected $guarded = [];

    protected $dates = ['end_at', 'closed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
