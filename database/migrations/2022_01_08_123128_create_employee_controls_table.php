<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class CreateEmployeeControlsTable extends Migration
{
    /**
     * @var string
     */
    protected string $table = 'employee_controls';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * We Will Store Countries & Insurance List &
         * Sections List & Companies & Guaranty
         */
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('type')->nullable();
            $table->boolean('active')->default(1);
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
