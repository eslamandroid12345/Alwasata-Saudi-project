<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $guarded =[];

    protected $fillable = [
      
        'status',
        'user_id',
        'recive_id',
        'req_id',
   
    ];

    protected $table ='tasks';


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recive(){
        return $this->belongsTo(User::class, 'recive_id');
    }

    public function requests(){
        return $this->belongsTo(request::class, 'req_id');
    }

    public function task_content(){
        return $this->hasMany(task_content::class,'task_id');
    }
}
