<?php

namespace App\Observers;

use App\ClientSubscriptionPayment;
use App\Mail\NewPayment;
use Illuminate\Support\Facades\Mail;

class ClientSubscriptionPaymentObserver
{
    /**
     * Handle the client subscription payment "created" event.
     *
     * @param \App\ClientSubscriptionPayment $clientSubscriptionPayment
     * @return void
     */
    public function created(ClientSubscriptionPayment $clientSubscriptionPayment)
    {
        $valor = $clientSubscriptionPayment->price;
        $cliente = $clientSubscriptionPayment->subscription->client->name;
        Mail::to(getenv('ADM_NOTIFICATION'))->send(new NewPayment(['valor' => $valor, 'cliente' => $cliente]));
    }

    /**
     * Handle the client subscription payment "updated" event.
     *
     * @param \App\ClientSubscriptionPayment $clientSubscriptionPayment
     * @return void
     */
    public function updated(ClientSubscriptionPayment $clientSubscriptionPayment)
    {
        //
    }

    /**
     * Handle the client subscription payment "deleted" event.
     *
     * @param \App\ClientSubscriptionPayment $clientSubscriptionPayment
     * @return void
     */
    public function deleted(ClientSubscriptionPayment $clientSubscriptionPayment)
    {
        //
    }

    /**
     * Handle the client subscription payment "restored" event.
     *
     * @param \App\ClientSubscriptionPayment $clientSubscriptionPayment
     * @return void
     */
    public function restored(ClientSubscriptionPayment $clientSubscriptionPayment)
    {
        //
    }

    /**
     * Handle the client subscription payment "force deleted" event.
     *
     * @param \App\ClientSubscriptionPayment $clientSubscriptionPayment
     * @return void
     */
    public function forceDeleted(ClientSubscriptionPayment $clientSubscriptionPayment)
    {
        //
    }
}
