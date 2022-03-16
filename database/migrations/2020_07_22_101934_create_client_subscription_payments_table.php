<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientSubscriptionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_subscription_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_subscription_id');
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('file')->nullable();
            $table->date('pay_at');
            $table->timestamps();

            $table->foreign('client_subscription_id')->references('id')->on('client_subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_subscription_payments');
    }
}
