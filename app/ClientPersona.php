<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientPersona extends Model
{
    protected $guarded = [];
    protected $dates = ['dob', 'created_at', 'updated_at'];
    /**
     * @var mixed
     */
    private $client;


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function addresses()
    {
        return $this->hasMany(ClientPersonaAddress::class);
    }

    public function emails()
    {
        return $this->hasMany(ClientPersonaEmail::class, 'client_persona_id');
    }

    public function phones()
    {
        return $this->hasMany(ClientPersonaPhone::class);
    }

    public function fullAddress()
    {
        $addresses = $this->addresses();
        if (!$addresses->count()) {
            throw new \Exception("#{$this->id} - {$this->name} não tem nenhum endereço cadastrado");
        }

        if (!$addresses->where('main', 1)->first()) {
            throw new \Exception("#{$this->id} - {$this->name} não tem um endereço setado como principal para puxar os dados");
        }

        $address = $addresses->where('main', 1)->first();
        $a = $address->street;
        if ($address->number) {
            $a .= ', ' . $address->number;
        }
        if ($address->complement) {
            $a .= ' - ' . $address->complement . '';
        }

        $a .= ' - ' . $address->neighborhood . ' - ' . $address->city . ' - ' . $address->state . ' - ' . $address->zip;

        return $a;
    }
}
