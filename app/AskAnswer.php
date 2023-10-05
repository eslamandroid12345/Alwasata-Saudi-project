<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AskAnswer extends Model
{
    protected $guarded=['id'];

    public function ask(){
        return $this->belongsTo('App\Ask');
    }

    public function customer(){
        return $this->belongsTo(customer::class);
    }

    public function request(){
        return $this->belongsTo(request::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
