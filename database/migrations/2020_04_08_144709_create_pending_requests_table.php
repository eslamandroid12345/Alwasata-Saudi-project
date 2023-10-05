<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('statusReq')->nullable()->default(1);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('collaborator_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('joint_id')->nullable();
            $table->unsignedBigInteger('real_id')->nullable();
            $table->unsignedBigInteger('fun_id')->nullable();
            $table->string('source')->nullable();
            $table->date('req_date')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('pending_requests');
    }
}
