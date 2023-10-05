<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * add_customer_reason_for_rejected_requests_table
 */
class AddCustomerReasonForRejectedRequestsTable extends Migration
{
    protected $table = 'requests';
    protected $column = 'customer_reason_for_rejected';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->text($this->column)->after('customer_found_property')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropColumn($this->column);
        });
    }
}
