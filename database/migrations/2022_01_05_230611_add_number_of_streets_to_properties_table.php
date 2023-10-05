<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddNumberOfStreetsToPropertiesTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'properties';
    /**
     * @var string
     */
    protected string $column = 'number_of_streets';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, fn(Blueprint $table) => $table->integer($this->column)->nullable());
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
