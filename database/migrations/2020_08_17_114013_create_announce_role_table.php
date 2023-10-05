<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnounceRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announce_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('role');
            $table->bigInteger('announce_id')->unsigned();

            $table->foreign('announce_id')->references('id')->on('announcements')->onDelete('cascade');
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
        Schema::dropIfExists('announce_role');
    }
}
