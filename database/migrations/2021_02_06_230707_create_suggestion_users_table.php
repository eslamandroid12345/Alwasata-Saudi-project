<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestionUsersTable extends Migration
{
    protected $table = 'suggestion_users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('suggestable_type')->nullable();
            $table->unsignedBigInteger('suggestable_id')->nullable();
            $table->enum('vote', ['yes', 'no']);
            $table->text('no_reason')->nullable();
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
