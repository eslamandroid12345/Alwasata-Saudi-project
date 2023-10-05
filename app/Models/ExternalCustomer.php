<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToClassification;
use App\Traits\BelongsTo\BelongsToUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalCustomer extends BaseModel
{
    use BelongsToUser;
    use BelongsToClassification;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id',
        'classification_id',
        'funding_id',
        'id_number',
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
        'hiring_date_hijri',
        'madany_id',
        'job_title',
        'askary_id',
        'military_rank',
        'salary',
        'without_transfer_salary',
        'add_support_installment_to_salary',
        'basic_salary',
        'guarantees',
        'salary_id',
        'is_supported',
        'has_joint',
        'has_obligations',
        'obligations_value',
        'duration_of_obligations',
        'has_financial_distress',
        'financial_distress_value',
        'status',
        'region_ip',
        'login_time',
        'logout',
        'login_from',
        'welcome_message',
        'notes',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'salary'                  => 'double',
        'basic_salary'            => 'double',
        'obligations'             => 'double',
        'duration_of_obligations' => 'int',
        'birth_date'              => 'datetime',
        'hiring_date'             => 'datetime',
        'is_supported'            => 'bool',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    // protected $attributes = [
    //     'user_id'                 => null,
    //     'classification_id'       => null,
    //     'name'                    => null,
    //     'mobile'                  => null,
    //     'id_number'               => null,
    //     'salary'                  => 0.0,
    //     'basic_salary'            => 0.0,
    //     'obligations'             => 0.0,
    //     'duration_of_obligations' => 0,
    //     // 'work_source_id'          => null,
    //     // 'askary_work_id'          => null,
    //     // 'madany_work_id'          => null,
    //     'birth_date'              => null,
    //     'is_supported'            => !0,
    //     'hiring_date'             => null,
    // ];

}
