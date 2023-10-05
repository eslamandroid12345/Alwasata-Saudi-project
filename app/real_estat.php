<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; //to take date

class real_estat extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'age',
        'city',
        'region',
        'status',
        'cost',
        'type',
        'other_value',
        'mortgage',
        'pursuit',
        'evaluated',
        'has_property',
        'financing_or_tsaheel',
        'evaluation_amount'
    ];



    public function request(){
        return $this->hasOne(request::class);
    }


    public function findCity($city){

        $checkCity = DB::table('cities')->where('value', $city)->first();

        if (!empty($checkCity))
        return $checkCity->id; // just get id of madany works

        else{ // we have to add a new one and get the id :)

            $resultId =  DB::table('cities')->insertGetId(
                array( //add it once use insertGetId
                    'value' => $city,
                )
            );

            return $resultId;

        }
    }
}
