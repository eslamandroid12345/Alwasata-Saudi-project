<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyPerformancesTable extends Migration
{
    protected $table = 'daily_performances';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();

            $table->date('today_date');


            $table->integer('total_recived_request')->default(0); //sum : received_basket + move_request_to

            $table->integer('received_basket')->default(0);

            $table->integer('star_basket')->default(0);
            $table->integer('followed_basket')->default(0);
            $table->integer('archived_basket')->default(0);
            $table->integer('sent_basket')->default(0);
            $table->integer('completed_request')->default(0);

            $table->integer('opened_request')->default(0);
            $table->integer('updated_request')->default(0);

            $table->integer('missed_reminders')->default(0);

            $table->integer('received_task')->default(0);
            $table->integer('replayed_task')->default(0);

            $table->integer('move_request_from')->default(0);
            $table->integer('move_request_to')->default(0);


            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
