<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyStreetWidth extends Model
{
    protected $table = 'property_street_widths';
    protected $fillable = [
      'property_id','width'
    ];
}
