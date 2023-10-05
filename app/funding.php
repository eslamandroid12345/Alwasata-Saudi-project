<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Support\Facades\DB;
use Carbon\Carbon; //to take date

class funding extends Model
{
    protected $fillable = [
        'funding_source',
        'funding_duration',
        'personalFun_pre',
        'extendFund_cost',
        'personalFun_cost',
        'realFun_pre',
        'realFun_cost',
        'ded_pre',
        'monthly_in',
        'product_code',
        'flexiableFun_cost',
        'monthly_installment_after_support',
        'personal_monthly_installment',
        'personal_salary_deduction',
    ];




    public function request(){
        return $this->hasOne(request::class);
    }


    public function fundBank($bank){


        if ($bank=='مصرف الإنماء')
        return 3;

        if ($bank=='بنك الأهلي' || $bank=='البنك الأهلي التجاري' )
        return 13;

        if ($bank=='مصرف الراجحي')
        return 12;

        $checkBank = DB::table('funding_sources')->where('value', $bank)->first();

        if (!empty($checkBank))
        return $checkBank->id; // just get id of madany works


        else{ // we have to add a new one and get the id :)

            $resultId =  DB::table('funding_sources')->insertGetId(
                array( //add it once use insertGetId
                    'value' => $bank,
                )
            );

            return $resultId;

        }
    }
}
