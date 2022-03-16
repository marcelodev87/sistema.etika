<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientProcessPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_process_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_process_id');
            $table->decimal('value', 10,2)->default(0);
            $table->date('payed_at')->nullable();
            $table->string('file')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('client_process_id')->references('id')->on('client_processes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_process_payments');
    }
}
