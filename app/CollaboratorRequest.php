<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaboratorRequest extends Model
{
    protected $guarded=['id'];

    public function user()
    {
        return $this->belongsTo("App\User",'user_id','id');
    }

    public function request()
    {
        return $this->belongsTo("App\request",'req_id','id');
    }
}
