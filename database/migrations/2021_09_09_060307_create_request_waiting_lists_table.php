<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestWaitingListsTable extends Migration
{
    protected $table = 'request_waiting_lists';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('action')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('req_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('active')->default(0);
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
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
