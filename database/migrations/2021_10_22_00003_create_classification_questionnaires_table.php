<?php

use App\Models\Classification;
use App\Models\ClassificationQuestionnaire as Model;
use App\Models\Request;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationQuestionnairesTable extends Migration
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
            $table->bigInteger('user_id')->nullable();
            $table->integer('classification_id');
            $table->integer('request_id');
            $table->string('title')->nullable();
            $table->string('body')->nullable();
            $table->boolean('value')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
