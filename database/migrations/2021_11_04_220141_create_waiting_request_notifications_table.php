<?php

use App\WaitingRequestNotification as Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaitingRequestNotificationsTable extends Migration
{
    protected $table;

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
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_id');
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waiting_request_notifications');
    }
}
