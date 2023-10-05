<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingAndAgent extends Model
{
    protected $guarded =['id'];

    protected $table ='training_and_agent';


    public function user(){
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function training(){
        return $this->belongsTo(User::class, 'training_id');
    }

}
