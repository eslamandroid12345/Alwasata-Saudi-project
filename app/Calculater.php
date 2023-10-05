<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calculater extends Model
{
    protected $fillable = [
      
        'request_id',
        'bank_id',

        'work',
        'military_rank',

        'residential_support',
        'birth_hijri',
        'age',

        'salary',
        'basic_salary',
        'guarantees',
        'salary_bank_id',
        'add_support_installment_to_salary',
        'without_transfer_salary',

        'personal_salary_deduction',
        'salary_deduction',
        'funding_months',
        'personal_funding_months',
        'personal_bank_profit',
        'bank_profit',
        'early_repayment',

        'property_amount',
        'property_completed',
        'residence_type',

        'have_joint',
        'joint_age',
        'joint_birth_hijri',
        'joint_salary',
        'joint_basic_salary',
        'joint_work',
        'joint_military_rank',
        'joint_residential_support',
        'joint_add_support_installment_to_salary',
        'joint_salary_bank_id',
        'joint_early_repayment',

        'provide_first_batch',
        'first_batch_percentage',
        'first_batch_profit',
        'fees',
        'discount',
   
    ];

    public function request(){
        return $this->belongsTo(request::class, 'request_id');
    }

    protected $table ='calculaters';
}
