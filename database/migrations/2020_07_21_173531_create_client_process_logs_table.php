<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientProcessLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_process_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_process_id');
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->string('type');
            $table->bigInteger('refer_id');
            $table->timestamps();

            $table->foreign('client_process_id')->references('id')->on('client_processes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_process_logs');
    }
}
