<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnsToExternalCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_customers', function (Blueprint $table) {
            $table->integer('funding_id')->nullable();
            $table->unsignedInteger('military_rank')->nullable();
            $table->string('job_title')->nullable();
            $table->unsignedInteger('salary_id')->nullable();
            $table->Integer('login_from')->default(0);
            $table->Integer('app_downloaded')->default(0);
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->Integer('otp_value')->nullable();
            $table->string('pass_text')->nullable();
            $table->timestamp('birth_date_higri')->nullable();
            $table->string('age')->nullable();
            $table->Integer('age_years')->nullable();
            $table->string('sex')->nullable();
            $table->Integer('isVerified')->default(0);
            $table->Integer('sms_count')->default(0);
            $table->string('email')->nullable();
            $table->timestamp('hiring_date_hijri')->nullable();
            $table->Integer('without_transfer_salary')->default(0);
            $table->Integer('add_support_installment_to_salary')->default(0);
            $table->Integer('guarantees')->default(0);
            $table->boolean('has_joint')->nullable();
            $table->boolean('has_obligations')->nullable();
            $table->Integer('credit_installment')->nullable();
            $table->Integer('obligations_installment')->nullable();
            $table->Integer('remaining_obligations_months')->nullable();
            $table->boolean('has_financial_distress')->nullable();
            $table->Integer('financial_distress_value')->nullable();
            $table->Integer('status')->default(0);
            $table->Integer('message_status')->default(0);
            $table->text('region_ip')->nullable();
            $table->Integer('logout')->default(1);
            $table->timestamp('login_time')->nullable();
            $table->Integer('otp_resend_count')->default(0);
            $table->Integer('welcome_message')->default(0);
            $table->text('notes')->nullable();
            $table->foreign('funding_id')->references('id')->on('fundings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_customers', function (Blueprint $table) {
            $table->dropForeign(['funding_id']);
            // $table->dropColumn(['']);
        });
    }
}
