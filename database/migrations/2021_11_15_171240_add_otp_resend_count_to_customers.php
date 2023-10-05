<?php

use App\Models\Customer as Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtpResendCountToCustomers extends Migration
{
    protected $table;
    protected $column = 'otp_resend_count';

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
        !Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, fn(Blueprint $t) => $t->integer($this->column)->default(0));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, fn(Blueprint $t) => $t->dropColumn($this->column));
    }
}
