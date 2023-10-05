<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasOne;


use App\Models\Request;

trait HasOneRequest
{

    public function request(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Request::class)->withDefault();
    }
}
