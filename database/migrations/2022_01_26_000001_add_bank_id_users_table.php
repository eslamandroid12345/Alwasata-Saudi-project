<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankIdUsersTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'users';

    /**
     * @var string
     */
    protected string $column = 'bank_id';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->unsignedInteger($this->column)->nullable();
            $table->foreign($this->column)->references('id')->on('banks')->onUpdate('cascade')->onDelete('cascade');
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
