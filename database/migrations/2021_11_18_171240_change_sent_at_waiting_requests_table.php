<?php

use App\WaitingRequest as Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSentAtWaitingRequestsTable extends Migration
{
    protected $table;
    protected $column = 'message_at';

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
        //$this->down();
        Schema::hasColumn($this->table, 'sent_at') && Schema::table($this->table, fn(Blueprint $t) => $t->renameColumn('sent_at', $this->column));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, fn(Blueprint $t) => $t->dropColumn($this->column));
    }
}
