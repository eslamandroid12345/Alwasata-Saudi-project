<?php

use App\Models\Classification;
use App\Models\Request;
use App\Models\ClassificationAlertSchedule as Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationAlertSchedulesTable extends Migration
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
            $table->integer('classification_id');
            $table->timestamp('send_time')->useCurrent();
            $table->integer('step')->default(0);
            $table->timestamps();

            $table->foreign('classification_id')->references('id')->on(Classification::getModelTable())->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('request_id')->references('id')->on(Request::getModelTable())->onDelete('cascade')->onUpdate('cascade');
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
