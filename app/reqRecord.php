<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reqRecord extends Model
{

    protected $fillable = [
        'value',
        'updateValue_at',
        'req_id',
        'user_switch_id',
        'colum',
        'user_id',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function request(){
        return $this->belongsTo(request::class, 'req_id');
    }


}
