<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPropertyRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_request', function (Blueprint $table) {
            $table->string('customer_email')->nullable();
            $table->unsignedBigInteger('property_type_id')->nullable();
            $table->double('min_price')->nullable();
            $table->double('max_price')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->double('distance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_request', function (Blueprint $table) {
            $table->dropColumn('customer_email')->nullable();
            $table->dropColumn('property_type_id')->nullable();
            $table->dropColumn('min_price')->nullable();
            $table->dropColumn('max_price')->nullable();
            $table->dropColumn('area_id')->nullable();
            $table->dropColumn('city_id')->nullable();
            $table->dropColumn('district_id')->nullable();
            $table->dropColumn('distance')->nullable();
        });
    }
}
