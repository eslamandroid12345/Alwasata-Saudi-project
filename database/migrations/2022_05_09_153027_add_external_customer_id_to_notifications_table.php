<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalCustomerIdToNotificationsTable extends Migration
{

     /**
     * @var string
     */
    protected string $table = 'notifications';

    /**
     * @var string
     */
    protected string $column = 'external_customer_id';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->bigInteger($this->column)->nullable();
            $table->foreign($this->column)->references('id')->on('external_customers')->onDelete('cascade');
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
