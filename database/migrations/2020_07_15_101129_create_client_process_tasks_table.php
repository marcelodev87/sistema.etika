<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientProcessTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_process_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('client_process_id')->nullable();
            $table->unsignedBigInteger('task_id');
            $table->decimal('price', 10,2)->default(0);
            $table->string('responsible_person')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('closed')->default(0);
            $table->dateTime('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('client_process_id')->references('id')->on('client_processes');
            $table->foreign('task_id')->references('id')->on('internal_tasks');
            $table->foreign('closed_by')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_process_tasks');
    }
}
