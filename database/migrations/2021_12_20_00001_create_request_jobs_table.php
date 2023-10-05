<?php

use App\Models\RequestJob as Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

;

class CreateRequestJobsTable extends Migration
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
            $table->string('action');
            $table->longText('data')->nullable();
            $table->timestamps();
            $table->foreign('request_id')->references('id')->on(\App\Models\Request::getModelTable())->onDelete('cascade')->onUpdate('cascade');
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
