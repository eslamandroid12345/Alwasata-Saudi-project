<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    protected $guarded=['id'];

    public function users()
    {
        return $this->hasMany('App\ScenariosUsers');
    }
}
