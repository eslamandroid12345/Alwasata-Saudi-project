<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWelcomeMessagesTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'welcome_message_settings';

    /**
     * @var string
     */
    protected string $column = 'request_source_id';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::hasColumn($this->table, $this->column) && Schema::table($this->table, function (Blueprint $table) {
            $table->dropForeign(['request_source_id']);
            $table->dropColumn($this->column);
        });

        Schema::create('w_m_s_request_source', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_source_id');
            $table->unsignedBigInteger('welcome_message_setting_id');
            $table->timestamps();
            $table->foreign('request_source_id')->references('id')->on('request_source')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('welcome_message_setting_id')->references('id')->on($this->table)->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('w_m_s_classification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('classification_id');
            $table->unsignedBigInteger('welcome_message_setting_id');
            $table->timestamps();
            $table->foreign('classification_id')->references('id')->on(\App\Models\Classification::getModelTable())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('welcome_message_setting_id')->references('id')->on($this->table)->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('w_m_s_request_source');
        Schema::dropIfExists('w_m_s_classification');
    }
}
