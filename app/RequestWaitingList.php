<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestWaitingList extends Model
{
    protected $guarded =['id'];
    protected $table ='request_waiting_lists';

    public function request(){
        return $this->belongsTo('App\request','req_id');
    }
    public function customer(){
        return $this->belongsTo('App\customer','customer_id');
    }
    public function user(){
        return $this->belongsTo('App\User','agent_id');
    }
    public function messages(){
        return $this->hasMany('App\WaitingMessage','req_id','req_id');
    }
}
