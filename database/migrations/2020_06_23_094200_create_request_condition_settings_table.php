<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestConditionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_condition_settings', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->string('request_validation_from_birth_date')->nullable();
            $table->string('request_validation_to_birth_date')->nullable();

            $table->string('request_validation_from_birth_hijri')->nullable();
            $table->string('request_validation_to_birth_hijri')->nullable();

            $table->string('request_validation_from_salary')->nullable();
            $table->string('request_validation_to_salary')->nullable();

            $table->string('request_validation_to_work')->nullable();
            $table->string('request_validation_to_support')->nullable();

            $table->string('request_validation_to_hasProperty')->nullable();
            $table->string('request_validation_to_hasJoint')->nullable();
            $table->string('request_validation_to_has_obligations')->nullable();
            $table->string('request_validation_to_has_financial_distress')->nullable();
        
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
        Schema::dropIfExists('request_condition_settings');
    }
}
