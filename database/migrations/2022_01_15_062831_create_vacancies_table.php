<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->tinyInteger('active')->default(1)->nullable();
            $table->tinyInteger('is_salary_deduction')->default(0)->nullable();
            $table->tinyInteger('is_vacations_deduction')->default(0)->nullable();
            $table->enum('type',['official','unofficial'])->nullable();
            $table->enum('gender',['male','female','both'])->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->tinyInteger('days')->nullable();
            $table->enum('days_commitment',['equal','min','max'])->nullable();
            $table->tinyInteger('count')->nullable();
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
        Schema::dropIfExists('vacancies');
    }
}
