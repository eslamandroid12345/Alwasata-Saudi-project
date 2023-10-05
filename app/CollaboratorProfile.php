<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaboratorProfile extends Model
{
    protected $guarded=["id"];

    public function user_id()
    {
        return $this->belongsTo("App\User");
    }
}
