<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class document extends Model
{
    protected $guarded=['id'];

    public function request()
    {
        return $this->belongsTo('App\request');
    }

    public function user()
    {
        return $this->belongsTo('App\user');
    }

    public function scopeRequestDocs(Builder $builder,$reqId)
    {
        $builder->where('req_id', $reqId);
    }
}
