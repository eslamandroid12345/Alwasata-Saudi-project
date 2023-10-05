<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersPhone extends Model
{
    protected $guarded=['id'];
    public function customer()
    {
        return $this->belongsTo('App\customer');
    }

    public function request()
    {
        return $this->belongsTo('App\request');
    }
}
