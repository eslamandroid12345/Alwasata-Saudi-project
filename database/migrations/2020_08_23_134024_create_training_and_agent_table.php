<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingAndAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_and_agent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_id')->nullable();
            $table->bigInteger('agent_id')->nullable();

            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('training_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('training_and_agent');
    }
}
