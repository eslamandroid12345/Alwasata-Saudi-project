<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VacancyRequest extends Model
{
    protected $guarded=['id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function vacancy()
    {
        return $this->belongsTo('App\Vacancy');
    }
}
