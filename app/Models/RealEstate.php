<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;

class RealEstate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'real_estats';
    protected $fillable = [
        'name',
        'age',
        'mobile',
        'city',
        'region',
        'status',
        'cost',
        'pursuit',
        'mortgage_value',
        'tsaheel_mortgage_value',
        'value_added',
        'assment_fees',
        'collobreator_cost',
        'net',
        'type',
        'other_value',
        'evaluated',
        'tenant',
        'mortgage',
        'has_property',
        'owning_property',
        'residence_type',
    ];
}
