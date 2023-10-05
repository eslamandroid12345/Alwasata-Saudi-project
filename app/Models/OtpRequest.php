<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;

class OtpRequest extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'otp_request';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile',
        'ip',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'mobile' => null,
        'ip'     => null,
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'mobile', 'mobile');
    }

}
