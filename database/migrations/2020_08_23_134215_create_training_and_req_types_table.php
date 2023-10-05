<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingAndReqTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_and_req_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_id')->nullable();
            $table->tinyInteger('type')->nullable()->comment = '0: purchase , 1: mortgage , 2: pur-mor , 3: mor-pur';

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
        Schema::dropIfExists('training_and_req_types');
    }
}
