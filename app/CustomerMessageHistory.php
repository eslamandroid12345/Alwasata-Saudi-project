<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerMessageHistory extends Model
{
    protected $guarded=['id'];

    public function customer()
    {
        return $this->belongsTo('App\customer');
    }
}
