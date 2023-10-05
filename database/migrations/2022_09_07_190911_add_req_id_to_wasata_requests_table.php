<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReqIdToWasataRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wasata_requestes', function (Blueprint $table) {
            $table->text('comment')->nullable();
            $table->enum('type', ['external', 'internal'])->default('external');
            $table->integer('user_id')->unsigned()->nullable(); // customers
            $table->integer('funding_user_id')->unsigned()->nullable(); // customers
            $table->integer('req_id')->unsigned()->nullable(); // customers
          /*  $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('funding_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('req_id')->references('id')->on('requests')->onUpdate('cascade')->onDelete('cascade');
        */});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wasata_requestes', function (Blueprint $table) {
            //
        });
    }
}
