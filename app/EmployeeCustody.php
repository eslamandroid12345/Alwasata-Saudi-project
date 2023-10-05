<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeCustody extends Model
{
    protected $guarded=['id'];

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function control()
    {
        return $this->belongsTo('App\EmployeeControl');
    }
}
