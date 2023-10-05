<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    protected  $table = 'employees';

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
            $table->string('name')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->enum('marital_status',['married','single','divorced','widow'])->nullable();
            $table->string('company_contract')->nullable();
            $table->string('work_date')->nullable();
            $table->string('work_date_2')->nullable();
            $table->string('work_end_date')->nullable();
            $table->string('contract_file')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('job')->nullable();
            $table->string('job_number')->nullable();
            $table->string('job_application')->nullable();
            $table->integer('family_count')->nullable();
            $table->text('qualification')->nullable();
            $table->string('residence_number')->nullable();
            $table->string('residence_end_date')->nullable();
            $table->string('direct_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('custody')->nullable();

            $table->unsignedBigInteger('control_section_id')->nullable();
            $table->unsignedBigInteger('control_subsection_id')->nullable();
            $table->unsignedBigInteger('control_nationality_id')->nullable();
            $table->unsignedBigInteger('control_guaranty_id')->nullable();
            $table->unsignedBigInteger('control_company_id')->nullable();
            $table->unsignedBigInteger('control_identity_id')->nullable();

            $table->unsignedBigInteger('control_insurances_id')->nullable();
            $table->unsignedBigInteger('control_work_id')->nullable();
            $table->unsignedBigInteger('control_medical_id')->nullable();

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
