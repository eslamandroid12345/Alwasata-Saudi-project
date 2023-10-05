<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAskAnswersTable extends Migration
{
    protected $table = 'ask_answers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('answer');
            $table->tinyInteger('surveyQC');
            $table->bigInteger('ask_id')->unsigned()->nullable();
            $table->integer('request_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->foreign('ask_id')->references('id')->on('asks')->onDelete('cascade');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists($this->table);
    }
}
