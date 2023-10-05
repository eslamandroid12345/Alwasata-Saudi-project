<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EditCalculationFormulaUser extends Model
{
    protected $guarded=['id'];

    public function user()
    {
        return $this->belongsTo('App\EditCalculationFormulaUser','user_id');
    }
}
