<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTechnicalOwnerIdToHelpDesksTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'help_desks';

    /**
     * @var string
     */
    protected string $column = 'technical_owner_id';

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
