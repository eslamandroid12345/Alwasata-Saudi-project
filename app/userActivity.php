<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HasPushSubscriptions;
class userActivity extends Model
{
    protected $guarded =[];

    protected $fillable = [
      
        'user_id',
        'last_activity',
        'sesstionID',
   
    ];

    protected $table ='user_activities';
    public $timestamp =false;
}
