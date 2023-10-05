<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    protected $fillable = [
        'value',
        'recived_id',
        'receiver_type',
        'req_id',
        'request_type',
        'task_id',
        'status',
        'type',
        'reminder_date',
    ];

    public  function request(){
        return $this->belongsTo(request::class ,'req_id');
    }

}
