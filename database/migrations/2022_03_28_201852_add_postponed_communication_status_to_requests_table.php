<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostponedCommunicationStatusToRequestsTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'requests';

    /**
     * @var string
     */
    protected string $column = 'postponed_communication_status';
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->boolean($this->column)->after('customer_want_to_contact_date')->nullable();
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
