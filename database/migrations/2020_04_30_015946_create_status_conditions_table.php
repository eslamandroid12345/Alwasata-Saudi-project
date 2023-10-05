<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('status')->nullable();  // because id in user table has increments type.
            $table->bigInteger('cond_id')->unsigned()->nullable(); // because id in classification table has bigIncrements type.
            $table->timestamps();

            $table->foreign('cond_id')->references('id')->on('request_conditions')->onDelete('cascade');

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_conditions');
    }
}
