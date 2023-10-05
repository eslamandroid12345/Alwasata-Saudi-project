<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomerResolveProblemToRequests extends Migration
{
    protected $table = 'requests';
    protected $column = 'customer_resolve_problem';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->tinyInteger($this->column)->after('customer_want_to_reject_req')->nullable();
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
