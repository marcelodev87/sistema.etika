<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function members()
    {
        return $this->hasMany(ClientPersona::class);
    }

    public function processes()
    {
        return $this->hasMany(ClientProcess::class);
    }

    public function tasks()
    {
        return $this->hasMany(ClientTask::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ClientSubscription::class);
    }

    public function subscriptionTasks()
    {
        return $this->hasMany(ClientSubscriptionTask::class, 'client_id');
    }

    public function mandatos()
    {
        return $this->hasMany(ClientMandato::class);
    }

    public function fullAddress()
    {
        $a = null;
        if ($this->street) {
            $a .= $this->street;
        }

        if ($this->street_number) {
            $a .= ', ' . $this->street_number;
        }
        if ($this->complement) {
            $a .= ' (' . $this->complement . ')';
        }

        $a .= ', ' . $this->city . ', ' . $this->neighborhood . ' - ' . $this->state . ' - ' . $this->zip;

        return $a;
    }
}
