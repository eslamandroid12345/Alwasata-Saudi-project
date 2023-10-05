<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['from', 'to', 'message', 'is_read', 'message_type'];

    const message_type = [
        'image',
        'video',
        'text'
    ];

    public function sender(){
        return $this->belongsTo('App\User','from');
    } 

    public function senderCustomer(){
        return $this->belongsTo('App\customer','from');
    } 


    // public function getMessageAttribute($value)
    // {
    //     if ($this->message_type == 'text'){
    //         return $value;
    //     } else{
    //         return url("storage/$value");
    //     }
    // }

    public function to() // message to user
    {
        return $this->morphTo();
    }

}
