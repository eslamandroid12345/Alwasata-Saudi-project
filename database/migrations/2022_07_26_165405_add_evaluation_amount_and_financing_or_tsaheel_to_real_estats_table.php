<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvaluationAmountAndFinancingOrTsaheelToRealEstatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('real_estats', function (Blueprint $table) {
            $table->string("financing_or_tsaheel")->nullable();
            $table->double("evaluation_amount")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('real_estats', function (Blueprint $table) {
            //
        });
    }
}
