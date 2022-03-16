<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientMandato extends Model
{
    protected $guarded = [];
    protected $dates = ['start_at', 'end_at'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
