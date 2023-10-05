<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScenariosUsers extends Model
{
    protected $guarded=['id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scenario()
    {
        return $this->belongsTo('App\Scenario');
    }
}
