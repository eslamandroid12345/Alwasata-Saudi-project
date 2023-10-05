<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\GuestCustomer;
use App\Alpha\BaseModel;
use App\Traits\HasPushTokensTrait;
use App\Traits\HasOne\HasOneRequest;
use App\Traits\BelongsTo\BelongsToUser;
use App\Traits\HasMany\HasManyCustomerPhone;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends BaseModel
{
    use BelongsToUser;
    use HasPushTokensTrait;
    use HasOneRequest;
    use HasManyCustomerPhone;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'app_downloaded',
        'username',
        // 'fcm_token',
        'email',
        'password',
        'mobile',
        'message_status',
        'pass_text',
        'isVerified',
        'otp_value',
        'otp_resend_count',
        'birth_date',
        'birth_date_higri',
        'age',
        'age_years',
        'sex',
        'work',
        'hiring_date',
        'madany_id',
        'job_title',
        'askary_id',
        'military_rank',
        'salary',
        'without_transfer_salary',
        'add_support_installment_to_salary',
        'basic_salary',
        'guarantees',
        'user_id',
        'salary_id',
        'is_supported',
        'has_joint',
        'has_obligations',
        'obligations_value',
        'has_financial_distress',
        'financial_distress_value',
        'status',
        'region_ip',
        'login_time',
        'logout',
        'login_from',
        'welcome_message',
        "app_rate_starts",
        "app_rate_comment",
        "date_of_rate",
        "is_processed",
    ];
    protected $hidden = [
      'password'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function otpRequests(): HasMany
    {
        return $this->hasMany(OtpRequest::class, 'mobile', 'mobile');
    }

    public function guestCustomers(){
        return $this->hasMany(GuestCustomer::class,'mobile','mobile')->withTrashed();
    }

    public function request() : HasOne
    {
        return $this->hasOne(\App\Models\Request::class,'customer_id');
    }

}
