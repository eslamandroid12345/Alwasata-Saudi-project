<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelcomeMessageSettingsTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'welcome_message_settings';

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
            $table->integer('request_source_id');
            $table->longText('welcome_message');
            $table->integer('time');
            $table->timestamps();

            $table->foreign('request_source_id')->references('id')->on('request_source')->onDelete('cascade')->onUpdate('cascade');
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
