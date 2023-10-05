<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollaboratorIdToCollaboratorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborator_requests', function (Blueprint $table) {
            $table->integer("collaborato_id")->nullable();
            $table->enum("status",["pulled","send","other"])->default("other");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaborator_requests', function (Blueprint $table) {
            //
        });
    }
}
