<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundingYearsTable extends Migration
{
    protected $table = 'funding_years';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('apiId');
            $table->bigInteger('value');
            $table->bigInteger('key');
            $table->string('text');
            $table->bigInteger('bank_id');
            $table->string('bank_code')->unique();
            $table->string('bank_id_to_string');
            $table->boolean('residential_support');
            $table->boolean('guarantees');
            $table->unsignedBigInteger('job_position_id');
            $table->string('job_position_code')->unique();
            $table->string('job_position_id_to_string');
            $table->enum('residential_support_to_string', ['no', 'yes']);
            $table->enum('guarantees_to_string', ['no', 'yes']);
            $table->string('years_to_string');
            $table->tinyInteger('years');
            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
