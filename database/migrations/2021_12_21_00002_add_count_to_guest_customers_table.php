<?php

use App\GuestCustomer as Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountToGuestCustomersTable extends Migration
{
    protected $table;
    protected $column = 'count';

    public function __construct()
    {
        $this->table = Model::getModelTable();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, fn(Blueprint $table) => $table->integer($this->column)->default(0)->after('has_request'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, fn(Blueprint $table) => $table->dropColumn($this->column));
    }
}
