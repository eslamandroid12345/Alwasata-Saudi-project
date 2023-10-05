<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HasPushSubscriptions;
class user_collaborator extends Model
{
    protected $guarded=['id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function collaborator()
    {
        return $this->belongsTo('App\User','collaborato_id');
    }
}
