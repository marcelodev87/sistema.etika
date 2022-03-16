<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_task', function (Blueprint $table) {
            $table->unsignedBigInteger('internal_process_id');
            $table->foreign('internal_process_id')->references('id')->on('internal_processes')->onDelete('cascade');
            $table->unsignedBigInteger('internal_task_id');
            $table->foreign('internal_task_id')->references('id')->on('internal_tasks')->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_task');
    }
}
