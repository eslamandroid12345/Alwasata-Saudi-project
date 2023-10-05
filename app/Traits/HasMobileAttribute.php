<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Trait HasMobileAttribute
 * @method static Builder ByMobile(string $mobile)
 * @package App\Traits
 */
trait HasMobileAttribute
{
    protected static $mobileColumnName = 'mobile';

    public static function getOrNew($mobile){
        if(($static = static::ByMobile($mobile)->first())){//ByMobile == scopeByMobile, static:: -> because the method static can only see static method
            return $static;
        }
        return new static( [ static::$mobileColumnName => $mobile ]);
    }

    public function scopeByMobile(Builder $builder, string $mobile){
        return $builder->where(static::$mobileColumnName, $this->parseMobile($mobile));// it will return bulider query
    }

    public function setMobileAttribute($mobile):void{
        $this->attributes['mobile'] = $this->parseMobile($mobile);
    }

    public function parseMobile($mobile){
        // parsing **
        // Todo: parse mobile number
        return $mobile;
        return substr($mobile,1);
    }
}
