<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ask extends Model
{
    protected $guarded=['id'];

    public function answers(){
        return $this->hasMany('App\AskAnswer');
    }
}
