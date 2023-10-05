<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'message',
        'mobile',
        'sent',
        'data',
        'read',
    ];

    /**
     * The model's attributes.
     *
     * @var array<string,mixed>
     */
    protected $attributes = [
        'message' => null,
        'mobile'  => null,
        'sent'    => !1,
        'data'    => '{}',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'data' => 'array',
        'sent' => 'bool',
    ];

    public static function log($message, $mobile, $data = null, $sent = !1)
    {
        if (!$data) {
            $data = '{}';
        }
        if($data)
        {
            $customer = Customer::where('mobile',$mobile)->first();
            $customer->increment('sms_count');
        }
        return static::create([
            'message' => $message,
            'mobile'  => $mobile,
            'data'    => $data,
            'sent'    => $sent,
        ]);


    }
}
