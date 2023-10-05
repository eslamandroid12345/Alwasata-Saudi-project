<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $guarded=['id'];

    public function customer()
    {
        return $this->belongsTo('App\customer');
    }
}
