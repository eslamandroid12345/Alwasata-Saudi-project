<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReqClassIdAgentToQualityReqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quality_reqs', function (Blueprint $table) {
            $table->bigInteger('req_class_id_agent')->nullable();
            $table->foreign('req_class_id_agent')->references('id')->on('classifcations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quality_reqs', function (Blueprint $table) {
            $table->dropForeign(['req_class_id_agent']);
            $table->dropColumn('req_class_id_agent');
        });
    }
}
