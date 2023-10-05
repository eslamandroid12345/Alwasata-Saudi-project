<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->string('first_name')->nullable();//الاسم الاول
            $table->string('sur_name')->nullable();//اسم العائله
            $table->date('date_of_birth')->nullable();//تاريخ الميلاد         
            $table->string('phone')->nullable();//رقم الجوال
            $table->string('email')->nullable();//البريد الالكترونى
            $table->double('salary')->nullable();//الراتب المتوقع بالريال
            $table->enum('gender',['male','female']);//الجنس


            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->foreign('nationality_id')->references('id')->on('nationalities')->onDelete('cascade');

            $table->string('other_nationality')->nullable();

            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

            $table->string('other_city')->nullable();
            
            $table->date('graduation_date')->nullable();//تاريخ التخرج

            $table->unsignedBigInteger('university_id')->nullable();
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');

            $table->string('other_university')->nullable();
            
            $table->string('level')->nullable();//المؤهل //other_level
            $table->string('level_specialization')->nullable();//تحصص المؤهل
            $table->string('grade')->nullable();// التقدير // other_grade
            $table->string('specialization')->nullable();//التخصص المرغوب

            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('job_titles')->onDelete('cascade');

            //$table->string('other_job')->nullable();
            
            $table->string('duration')->nullable();// طبيعه الدوام
            $table->date('possible_start_date');//متى تستطيع ان تبدا
            $table->string('experance_years')->nullable();// سنوات الخبره // other_experance_years
            
            $table->longText('notes')->nullable();//كلمنا عن نفسك

            $table->softDeletes();
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
        Schema::dropIfExists('job_applications');
    }
}
