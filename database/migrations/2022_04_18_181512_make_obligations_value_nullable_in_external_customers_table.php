<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeObligationsValueNullableInExternalCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_customers', function (Blueprint $table) {
            $table->double('obligations_value')->nullable()->change();
            $table->integer('duration_of_obligations')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_customers', function (Blueprint $table) {
            //
        });
    }
}
