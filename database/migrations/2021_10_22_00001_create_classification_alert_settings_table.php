<?php

use App\Models\Classification;
use App\Models\ClassificationAlertSetting as Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationAlertSettingsTable extends Migration
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
            $table->integer('classification_id');
            $table->integer('step');
            $table->integer('hours_to_send')->default(0);
            $table->string('type');
            $table->timestamps();

            $table->foreign('classification_id')->references('id')->on(Classification::getModelTable())->onDelete('cascade')->onUpdate('cascade');
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
