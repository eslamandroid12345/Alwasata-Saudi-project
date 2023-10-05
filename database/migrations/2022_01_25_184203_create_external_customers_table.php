<?php

use App\funding;
use App\Models\Classification;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalCustomersTable extends Migration
{
    protected $table = 'external_customers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            // $table->bigInteger('funding_id')->unsigned()->nullable();
            $table->integer('classification_id')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('id_number')->nullable();
            $table->double('salary')->default(0);
            $table->double('basic_salary')->default(0);
            $table->double('obligations')->default(0);
            $table->integer('duration_of_obligations')->default(0);
            $table->unsignedBigInteger('work_source_id')->nullable();
            $table->unsignedInteger('askary_work_id')->nullable();
            $table->unsignedInteger('madany_work_id')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->boolean('is_supported');
            $table->timestamp('hiring_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on(User::getModelTable())->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('funding_id')->references('id')->on(funding::getModelTable())->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('classification_id')->references('id')->on(Classification::getModelTable())->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists($this->table);
    }
}
