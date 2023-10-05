<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculatersTable extends Migration
{
    protected $table = 'calculaters';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('request_id')->nullable();
            $table->integer('bank_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('switch_id')->nullable();

            $table->string('work')->nullable(); // work in customers
            $table->string('military_rank')->nullable(); // military_rank in customers

            $table->boolean('residential_support')->nullable(); // is_supported in customers
            $table->string('birth_hijri')->nullable();
            $table->integer('age')->nullable(); // age_years in customers

            $table->float('salary')->nullable(); // salary in customers
            $table->float('basic_salary')->nullable(); // basic_salary in customers
            $table->boolean('guarantees')->nullable(); // guarantees in customers
            $table->integer('salary_bank_id')->nullable();
            $table->boolean('add_support_installment_to_salary')->nullable(); //add_support_installment_to_salary in customers
            $table->boolean('without_transfer_salary')->nullable(); //without_transfer_salary in customers

            $table->integer('personal_salary_deduction')->nullable(); //personal_salary_deduction in fundings
            $table->float('personal_monthly_installment')->nullable(); //personal_monthly_installment in fundings
            $table->integer('salary_deduction')->nullable(); //ded_pre in fundings
            $table->float('monthly_installment')->nullable(); //monthly_in in fundings
            $table->float('monthly_installment_after_support')->nullable(); //monthly_installment_after_support in fundings
            $table->integer('funding_months')->nullable(); //funding_months in fundings
            $table->integer('personal_funding_months')->nullable(); //personal_funding_months in fundings
            $table->integer('personal_bank_profit')->nullable(); //	personalFun_pre in fundings
            $table->integer('bank_profit')->nullable(); //	realFun_pre in fundings
            $table->float('flexiableFun_cost')->nullable(); //	flexiableFun_cost in fundings
            $table->float('realFun_cost')->nullable(); //	realFun_cost in fundings
            $table->float('personalFun_cost')->nullable(); //	personalFun_cost in fundings
            $table->integer('has_obligations')->nullable(); //has_obligations in customers
            $table->integer('early_repayment')->nullable(); //obligations_value in customers

            $table->integer('property_amount')->nullable(); //cost in  real_estats
            $table->boolean('property_completed')->nullable(); //status in  real_estats
            $table->integer('residence_type')->nullable(); // residence_type in  real_estats

            $table->boolean('have_joint')->nullable(); // has_joint  in customers
            $table->integer('joint_age')->nullable(); // age_years in joints
            $table->string('joint_birth_hijri')->nullable();
            $table->float('joint_salary')->nullable(); //salary in joints
            $table->float('joint_basic_salary')->nullable(); //basic_salary in joints
            $table->string('joint_work')->nullable(); // work in joints
            $table->string('joint_military_rank')->nullable(); // military_rank in joints
            $table->boolean('joint_residential_support')->nullable(); //is_supported in joints
            $table->boolean('joint_add_support_installment_to_salary')->nullable(); //add_support_installment_to_salary in joints
            $table->integer('joint_salary_bank_id')->nullable();
            $table->integer('joint_has_obligations')->nullable(); //has_obligations in joints
            $table->float('joint_early_repayment')->nullable(); //obligations_value in joints

            $table->boolean('provide_first_batch')->nullable(); //provide_first_batch in prepayments
            $table->integer('first_batch_percentage')->nullable(); //prepaymentPre in prepayments
            $table->integer('first_batch_profit')->nullable(); //prepaymentVal in prepayments
            $table->float('fees')->nullable(); //adminFee in prepayments
            $table->float('discount')->nullable(); // customer_discount in prepayments


            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('salary_bank_id')->references('id')->on('salary_sources')->onDelete('cascade');
            $table->foreign('joint_salary_bank_id')->references('id')->on('salary_sources')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('switch_id')->references('id')->on('users')->onDelete('cascade');

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
