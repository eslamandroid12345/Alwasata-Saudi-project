<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeControl extends Model
{
    protected $guarded=['id'];

    public function subsections()
    {
        return $this->hasMany('App\EmployeeControl','id');
    }

    public function scopeIsActive($builder)
    {
        return $builder->where('active',1);
    }

    public function section()
    {
        return $this->belongsTo('App\EmployeeControl','parent_id','id');
    }
}
