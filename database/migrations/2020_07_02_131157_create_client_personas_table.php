<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_personas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('name')->nullable();
            $table->string('document')->nullable();
            $table->string('role')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('gender');
            $table->string('natural')->default('Cidade');
            $table->string('profession')->nullable();
            $table->date('dob')->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('rg_expedidor', 20)->nullable();

            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_personas');
    }
}
