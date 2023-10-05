<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable();
            //$table->foreign('type_id')->references('id')->on('job_application_types')->onDelete('cascade');

            //hr is user with role 12
            $table->integer('hr_id')->nullable();
           // $table->foreign('hr_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('hr_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            //
        });
    }
}
