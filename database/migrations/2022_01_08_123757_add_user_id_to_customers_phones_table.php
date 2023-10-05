<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddUserIdToCustomersPhonesTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'customers_phones';

    /**
     * @var string
     */
    protected string $column = 'user_id';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->bigInteger($this->column)->nullable();
            $table->foreign($this->column)->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign([$this->column]);
            $table->dropColumn($this->column);
        });
    }
}
