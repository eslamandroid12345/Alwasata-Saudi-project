<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobApplicationExtraDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application_extra_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('job_app_id');
            $table->foreign('job_app_id')->references('id')->on('job_applications')->onDelete('cascade');

            $table->enum('type',['courses','experances']);
            $table->string('title')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

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
        Schema::dropIfExists('job_application_extra_details');
    }
}
