<?php

namespace App\Models;

use App\City;
use App\Models\JobTitle;
use App\Models\University;
use App\Models\Nationality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use SoftDeletes;
    
   // protected $fillable=['first_name','sur_name','date_of_birth','phone','email','salary','graduation_date','grade','specialization','possible_start_date','notes', 'nationality_id','city_id','university_id','job_id'];
    protected $fillable=[ 'first_name', 'sur_name', 'date_of_birth', 'phone', 'email', 'salary', 'gender', 'nationality_id', 'other_nationality', 'city_id', 'other_city', 'graduation_date', 'university_id', 'other_university', 'level', 'level_specialization', 'grade', 'specialization', 'job_id', 'duration', 'possible_start_date', 'experance_years', 'notes','type_id','hr_id','hr_notes','need_traning'];

    public function job_title(){
        return $this->belongsTo(JobTitle::class,'job_id');
    }

    public function nationality(){
        return $this->belongsTo(Nationality::class);
    }

    //تصنيف الطلبات
    public function type(){
        return $this->belongsTo(JobApplicationType::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function university(){
        return $this->belongsTo(University::class);
    }
}
