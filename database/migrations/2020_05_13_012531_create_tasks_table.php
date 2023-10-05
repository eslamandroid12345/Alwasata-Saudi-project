<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status')->default('0');
            $table->bigInteger('user_id')->nullable(); // because id in user table has increments type.
            $table->bigInteger('recive_id')->nullable(); // because id in user table has increments type.
            $table->integer('req_id')->nullable(); 
         

            $table->foreign('req_id')->references('id')->on('quality_reqs')->onDelete('cascade');
            $table->foreign('recive_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('tasks');
    }
}
