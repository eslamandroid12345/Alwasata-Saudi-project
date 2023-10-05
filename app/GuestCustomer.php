<?php

namespace App;

use App\Alpha\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestCustomer extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'birth_date',
        'work',
        'salary',
        'military_rank',
        'has_request',
        'count',
        'status',
    ];

    protected $attributes = [
        'count' => 1,
    ];

    public function customer(){
        return $this->belongsTo(\App\Models\Customer::class,'mobile','mobile');
    }
}
