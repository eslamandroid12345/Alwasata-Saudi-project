<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyRequest extends Model
{
    protected $table='property_request';
    protected $fillable = [
        'statusReq','customer_id','property_id','responsible_id',
        'source','req_date','comment','collaborator_comment',
        'class_id_propertyagent','class_id_collaborator',
        'customer_email',
        'property_type_id',
        'min_price',
        'max_price',
        'area_id',
        'city_id',
        'district_id',
        'distance',
    ];

    function customer(){
        return $this->belongsTo(customer::class,'customer_id' );
    }
    function property(){
        return $this->belongsTo(Property::class,'property_id' );
    }
    public function propertyType()
    {
        return $this->belongsTo(realType::class,'property_type_id' );
    }
    function responsible(){
        return $this->belongsTo(User::class,'responsible_id' );
    }
    function classification(){
        return $this->belongsTo(classifcation::class ,'class_id_propertyagent' );
    }
    function classification_collaborator(){
        return $this->belongsTo(classifcation::class ,'class_id_collaborator' );
    }

    function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }
    function area()
    {
        return $this->belongsTo(Area::class,'area_id');
    }
}

