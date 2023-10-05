<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task_content extends Model
{
    protected $guarded =[];

    protected $fillable = [
      
        'task_contents_status',
        'content',
        'date_of_content',
        'user_note',
        'date_of_note',
        'task_id',
   
    ];

    protected $table ='task_contents';

    public function task(){
        return $this->belongsTo(task::class, 'task_id');
    }
}
