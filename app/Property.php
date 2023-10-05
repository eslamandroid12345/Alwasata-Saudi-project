<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'creator_id','type_id','price_type',
        'fixed_price','min_price','max_price',
        'lng','lat','is_published',
        'has_offer','offer_price','city_id','area_id','district_id','address',
        'num_of_rooms','num_of_salons','num_of_kitchens','num_of_bathrooms',
        'description','video_url','last_notification_date','number_of_streets',
        'dev_name',"dev_number","mark_name","mark_number","owner_name","owner_number",
        "number_of_flats","area"
    ];

    function creator(){
        return $this->belongsTo(User::class,'creator_id');
    }

    function city(){
        return $this->belongsTo(City::class,'city_id' );
    }

    function area(){
        return $this->belongsTo(Area::class,'area_id' );
    }

    function areaName(){
        return $this->belongsTo(Area::class,'area_id' );
    }

    function district(){
        return $this->belongsTo(District::class,'district_id' );
    }

    function type(){
        return $this->belongsTo(realType::class ,'type_id' );
    }
    function image(){
        return $this->morphMany(Image::class, 'imageable');
    }
    function propertyCity(){
        return $this->belongsTo(cities::class ,'city' );
    }
}
