<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientPersonaEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_persona_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_persona_id');
            $table->string('email')->nullable();
            $table->boolean('main')->default(0);
            $table->timestamps();

            $table->foreign('client_persona_id')->references('id')->on('client_personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_persona_emails');
    }
}
