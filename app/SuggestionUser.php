<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuggestionUser extends Model
{
    protected $guarded=['id'];

    public function suggestable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
