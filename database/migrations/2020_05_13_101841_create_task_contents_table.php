<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('task_contents_status')->default('0');
            $table->text('content')->nullable();
            $table->timestamp('date_of_content')->nullable();
            $table->text('user_note')->nullable();
            $table->timestamp('date_of_note')->nullable();
            $table->bigInteger('task_id')->unsigned()->nullable(); 
         
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
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
        Schema::dropIfExists('task_contents');
    }
}
