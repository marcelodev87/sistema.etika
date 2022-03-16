<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientProcess extends Model
{
    protected $guarded = [];

    public function tasks(): HasMany
    {
        return $this->hasMany(ClientProcessTask::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(InternalProcess::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ClientProcessPayment::class);
    }

    public function totalPrice(): int
    {
        $valorProcesso = $this->price;
        $valorExtra = $this->tasks()->sum('price');
        $valorTotal = $valorProcesso + $valorExtra;
        return $valorTotal;
    }

    public function totalPayed(): int
    {
        return $this->payments()->sum('value');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ClientProcessLog::class);
    }
}
