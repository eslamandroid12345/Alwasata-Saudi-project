<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelRequestCountToRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected string $table = 'requests';
    protected string $column = 'cancel_request_count';
    public function up()
    {
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->integer($this->column)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->dropColumn($this->column);
        });
    }
}
