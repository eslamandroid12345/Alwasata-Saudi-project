<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationForRequestWithoutUpdateTable extends Migration
{
    protected $table = 'classification_for_request_without_update';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('class_id')->nullable();

            $table->foreign('class_id')->references('id')->on('classifcations')->onDelete('cascade');
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
