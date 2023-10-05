<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaitingMessage extends Model
{

    protected $guarded=['id'];
    protected $fillable = ['message_time','message_value','req_id','message_type'];

    public function request(){
        return $this->belongsTo('App\request','req_id');
    }
}
