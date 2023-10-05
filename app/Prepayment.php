<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prepayment extends Model
{
    protected $fillable = [
        'payStatus',
        'visa',
	];
}
