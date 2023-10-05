<?php

namespace App;

use GeniusTS\HijriDate\Hijri;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Employee extends Model implements HasMedia
{
    use HasMediaTrait;

    public $timestamps = false;
    protected $guarded = ['id'];
    protected $appends = ['marital','genderName',
          'birth_date_m',"residence_end_date_m","work_end_date_m","work_date_m",
          "work_date_2_m","direct_date_m"
    ];

    public function getBirthDateMAttribute(){
        return self::convertToGregorianDate($this->birth_date);
    }
    public function getResidenceEndDateMAttribute(){
        return self::convertToGregorianDate($this->residence_end_date);
    }
    public function getWorkEndDateMAttribute(){
        return self::convertToGregorianDate($this->work_end_date);
    }
    public function getWorkDateMAttribute(){
        return self::convertToGregorianDate($this->work_date);
    }
    public function getWorkDate2MAttribute(){
        return self::convertToGregorianDate($this->work_date_2);
    }
    public function getDirectDateMAttribute(){
        return self::convertToGregorianDate($this->direct_date);
    }

    public static function convertToGregorianDate($hijri)
    {
        $date = $hijri;
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $output = Hijri::convertToGregorian((int) $day, (int) $month, (int) $year);

        $year2 = substr($output, 0, 4);
        $month2 = substr($output, 5, 2);
        $day2 = substr($output, 8, 2);

        return $year2.'-'.$month2.'-'.$day2;
    }
    public function getGenderNameAttribute()
    {
        switch ($this->gender){
            case 'male' :
                return 'ذكر';
                break;
            case 'female':
                return 'أنثي';
                break;
            default:
                return 'غير محدد';
        }
    }
    protected $with=['company','section','subsection','insurances','medical','guaranty','identity','nationality','work'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getMaritalAttribute()
    {
        switch ($this->marital_status) {
            case 'single':
                return 'أعزب / عزباء';
            case 'married':
                return 'متزوج /ـة';
            case 'divorced':
                return 'مطلق /ـة';
            case 'widow':
                return 'أرمل /ـة';
        }
    }

    public function area()
    {
        return $this->belongsTo('App\Area','area_id','id');
    }

    public function city()
    {
        return $this->belongsTo('App\City','city_id','id');
    }

    public function district()
    {
        return $this->belongsTo('App\District','district_id','id');
    }



    public function company()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_company_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_section_id', 'id');
    }

    public function subsection()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_subsection_id', 'id');
    }

    public function insurances()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_insurances_id', 'id');
    }

    public function medical()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_medical_id', 'id');
    }

    public function guaranty()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_guaranty_id', 'id');
    }

    public function custodies()
    {
        return $this->hasMany('App\EmployeeCustody','employee_id','id');
    }

    public function guaranty_company()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_guaranty_company_id', 'id');
    }

    public function identity()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_identity_id', 'id');
    }

    public function nationality()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_nationality_id', 'id');
    }

    public function work()
    {
        return $this->belongsTo('App\EmployeeControl', 'control_work_id', 'id');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('companies')->singleFile();
        $this->addMediaCollection('job-applications')->singleFile();
        $this->addMediaCollection('contracts')->singleFile();
    }

    public function files()
    {
        return $this->belongsTo('App\EmployeeFile','user_id','user_id');
    }

}
