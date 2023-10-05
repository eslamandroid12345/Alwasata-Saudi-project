<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityRequestNeedTurned extends Model
{
    protected $table ='quality_request_need_turneds';

    protected $primaryKey = 'id';
    protected $attributes = [ //to define the default values 
        'status' => 0,
    ];
    ////because all Eloquent models are protected against mass assignment vulnerabilities by defaul
    protected $guarded =[]; //an empty array. If you choose to unguard your model, you should take special care to always hand-craft the arrays passed to Eloquent's fill, create, and update methods:
    protected $fillable = ['status','reject_reason','quality_id','quality_req_id','agent_req_id','previous_agent_id','new_agent_id'];



    /**
     * TASK OF MOVMENT
     */
    const TASK_OF_MOVMENT = 'تم نقل الطلب إليك ، يرجى المتابعة مع العميل';
    const TASK_OF_FOLLOW_UP = 'يرجى المتابعة مع العميل';


    public function quality_user()
    {
        return $this->belongsTo(User::class, 'quality_id');
    }

    public function previous_agent_user()
    {
        return $this->belongsTo(User::class, 'previous_agent_id');
    }

    public function new_agent_user()
    {
        return $this->belongsTo(User::class, 'new_agent_id');
    }

    public function quality_request()
    {
        return $this->belongsTo(quality_req::class, 'quality_req_id');
    }
}

