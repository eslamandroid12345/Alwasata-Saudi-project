<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaitingRequestsSettingsTable extends Migration
{
    protected $table = 'waiting_requests_settings';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('timeDays')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
