<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon; //to take date

class joint extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'salary',
        'age',
        'work',
   //    'birth_date' ,
        'birth_date_higri' ,
        'askary_id',
        'madany_id',
        'military_rank',
        'job_title',
        'salary_id',
        'is_supported',
    ];
    


    public function request(){
        return $this->hasOne(request::class);
    }
    
    public function setMadanyWorkAttribute($value){
        $this->attributes['madany_id'] = $value;
    }

    public function setAskaryWorkAttribute($value){
        $this->attributes['askary_id'] = $value;
    }

    public function setSalaryAttribute($value){
        $this->attributes['salary_id'] = $value;
    }

    public function setMiliratyRankAttribute($value){
        $this->attributes['military_rank'] = $value;
    }


    public function madanyWork($work){
        
        $checkWork = DB::table('madany_works')->where('value', $work)->first();

        if (!empty($checkWork))
        return $checkWork->id; // just get id of madany works

        else{ // we have to add a new one and get the id :)

            $resultId =  DB::table('madany_works')->insertGetId( 
                array( //add it once use insertGetId
                    'value' => $work,
                )
            );

            return $resultId;

        }
    }


    public function askaryWork($work){
        
        $checkWork = DB::table('military_ranks')->where('value', $work)->first();

        if (!empty($checkWork))
        return $checkWork->id; // just get id of madany works

        else{ // we have to add a new one and get the id :)

            $resultId =  DB::table('military_ranks')->insertGetId( 
                array( //add it once use insertGetId
                    'value' => $work,
                )
            );

            return $resultId;

        }
    }

    public function salaryBank($bank){
        
        if ($bank == 'مصرف الراجحي')
        $bank= 'بنك الراجحي';
        
        $checkBank = DB::table('salary_sources')->where('value', $bank)->first();

        if (!empty($checkBank))
        return $checkBank->id; // just get id of madany works

        else{ // we have to add a new one and get the id :)

            $resultId =  DB::table('salary_sources')->insertGetId( 
                array( //add it once use insertGetId
                    'value' => $bank,
                )
            );

            return $resultId;

        }
    }

}
