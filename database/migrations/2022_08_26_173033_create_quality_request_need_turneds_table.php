<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQualityRequestNeedTurnedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_request_need_turneds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('quality_id')->nullable();
            $table->unsignedInteger('quality_req_id')->unsigned()->nullable(); 
            $table->unsignedInteger('agent_req_id')->unsigned()->nullable();
            $table->unsignedInteger('previous_agent_id')->unsigned()->nullable();
            $table->unsignedInteger('new_agent_id')->unsigned()->nullable();
            $table->string('reject_reason')->nullable();
            $table->integer('status')->nullable(); // 0: new , 1: completed with action, 2: completed with out action


            //$table->foreign('quality_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('quality_req_id')->references('id')->on('quality_reqs');
            //$table->foreign('agent_req_id')->references('id')->on('requests');
            //$table->foreign('previous_agent_id')->references('id')->on('users');
            //$table->foreign('new_agent_id')->references('id')->on('users');

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
        Schema::dropIfExists('quality_request_need_turneds');
    }
}
