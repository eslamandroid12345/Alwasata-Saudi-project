<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('contract_file');
            $table->dropColumn('job_application');
            $table->dropColumn('company_contract');

            $table->string('specialization')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();

            $table->string('street_name')->nullable();
            $table->string('building_number')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('title')->nullable();
            $table->string('contact_person_number')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_relation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
}
