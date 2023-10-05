<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaitingMessagesTable extends Migration
{
    protected $table = 'waiting_messages';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) &&
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('message_time')->nullable();
            $table->string('message_type')->nullable();
            $table->longText('message_value')->nullable();

            $table->timestamp('employee_replay_time')->nullable();
            $table->string('employee_message_type')->nullable();
            $table->longText('employee_message_value')->nullable();

            $table->integer('req_id')->nullable();
            $table->foreign('req_id')->references('id')->on('quality_reqs')->onDelete('cascade');
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
