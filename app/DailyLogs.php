<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyLogs extends Model
{
    protected $guarded=['id'];

    public function request()
    {
        return $this->belongsTo('App\Models\Request');
    }

    public function user_id()
    {
        return $this->belongsTo('App\User');
    }
}
