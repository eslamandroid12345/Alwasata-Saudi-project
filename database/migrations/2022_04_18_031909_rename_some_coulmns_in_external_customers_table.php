<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSomeCoulmnsInExternalCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_customers', function (Blueprint $table) {
            $table->renameColumn('obligations', 'obligations_value');
            $table->renameColumn('work_source_id', 'work');
            $table->renameColumn('askary_work_id', 'askary_id');
            $table->renameColumn('madany_work_id', 'madany_id');
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
