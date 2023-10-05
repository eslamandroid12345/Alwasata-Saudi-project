<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppDetailsTable extends Migration
{
   
    public function up()
    {
        Schema::create('app_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icon_name')->nullable();
            $table->string('icon_title')->nullable();
            $table->string('icon_desc')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_details');
    }
}
