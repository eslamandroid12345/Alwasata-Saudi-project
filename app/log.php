<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class log extends Model
{
    protected $fillable = ['user_id', 'status', 'type', 'time'];

}
