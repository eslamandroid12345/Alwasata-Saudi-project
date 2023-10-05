<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWasataRequestesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wasata_requestes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('external_customer_id')->unsigned()->nullable(); // customers
            $table->bigInteger('finance_supervisor_id')->unsigned()->nullable(); // request source

            $table->date('req_date')->nullable();
            $table->string('req_type')->nullable();
            // $table->enum('req_status',['تم ارساله','تم افراغه','تم سحبه'])->nullable();
            $table->integer('req_status')->unsigned()->nullable();
            $table->text('notes')->nullable();

            $table->foreign('external_customer_id')->references('id')->on('external_customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('finance_supervisor_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('wasata_requestes');
    }
}
