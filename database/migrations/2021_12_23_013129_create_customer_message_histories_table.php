<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerMessageHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_message_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type',['email','sms'])->nullable();
            $table->string('type_value')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->bigInteger('customer_id')->nullable();
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
        Schema::dropIfExists('customer_message_histories');
    }
}
