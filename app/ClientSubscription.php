<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientSubscription extends Model
{
    protected $guarded = [];
    protected $dates = ['terminate_at'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function payments()
    {
        return $this->hasMany(ClientSubscriptionPayment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
