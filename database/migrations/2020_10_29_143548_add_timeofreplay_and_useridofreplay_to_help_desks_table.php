<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeofreplayAndUseridofreplayToHelpDesksTable extends Migration
{
    protected $table = 'help_desks';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->table, function (Blueprint $table) {
            !Schema::hasColumn($this->table, 'date_replay') && $table->timestamp('date_replay')->nullable();
            if (!Schema::hasColumn($this->table, 'user_id')) {
                $table->bigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
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
            Schema::hasColumn($this->table, 'user_id') && $table->dropColumn('user_id');
            Schema::hasColumn($this->table, 'date_replay') && $table->dropColumn('date_replay');
        });
    }
}
