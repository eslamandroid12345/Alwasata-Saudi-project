<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacancyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vacancy_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('attachment')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes_manager')->nullable();
            $table->text('notes_hr')->nullable();

            $table->tinyInteger('is_replacement_exist')->nullable();
            $table->string('replacement_name')->nullable();

            $table->tinyInteger('is_exit_and_return')->nullable();
            $table->tinyInteger('is_need_ticket_exchange')->nullable();
            $table->enum('expenses',['company','user'])->nullable();

            $table->foreign('vacancy_id')->references('id')->on('vacancies')->onDelete('cascade');
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
        Schema::dropIfExists('vacancy_requests');

    }
}
