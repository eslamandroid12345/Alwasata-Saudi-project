<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaboratorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborator_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('req_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->unsignedBigInteger('pen_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('req_id')->references('id')->on('requests')->onDelete('cascade');
            $table->foreign('pen_id')->references('id')->on('pending_requests')->onDelete('cascade');

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
        Schema::dropIfExists('collaborator_requests');
    }
}
