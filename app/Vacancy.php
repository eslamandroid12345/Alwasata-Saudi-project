<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $guarded=['id'];
    protected $appends=['genderName'];

    public function getGenderNameAttribute()
    {
        switch ($this->gender){
            case 'male' :
                return 'ذكر';
                break;
            case 'female':
                return 'أنثي';
                break;
            case 'both':
                return 'للجنسين ';
                break;
            default:
                return 'غير محدد';
        }
    }

    public function requests()
    {
        return $this->hasMany('App\VacancyRequest');
    }
}
