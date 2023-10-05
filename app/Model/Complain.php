<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
