<?php

namespace App;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded =['id'];

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function districts()
    {
        return $this->hasMany('App\District');
    }

    public function job_application(){
        return $this->hasMany(JobApplication::class);
    }
    
}
