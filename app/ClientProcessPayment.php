<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProcessPayment extends Model
{
    protected $guarded = [];
    protected $dates = ['payed_at'];

    public function clientProcess(): BelongsTo
    {
        return $this->belongsTo(ClientProcess::class, 'client_process_id');
    }
}
