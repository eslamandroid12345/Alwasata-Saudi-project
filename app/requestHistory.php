<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class requestHistory extends Model
{
    protected $fillable = [
      
        'req_id',
        'user_id',
        'recive_id',
        'title',
        'content',
        'history_date',
   
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recive(){
        return $this->belongsTo(User::class, 'recive_id');
    }

    public function requests(){
        return $this->belongsTo(request::class, 'req_id');
    }

    
}
