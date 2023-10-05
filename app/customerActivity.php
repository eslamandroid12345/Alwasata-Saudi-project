<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customerActivity extends Model
{
    protected $guarded =[];

    protected $fillable = [
      
        'customer_id',
        'last_activity',
        'sesstionID',
   
    ];

    protected $table ='customer_activities';
    public $timestamp =false;
}
