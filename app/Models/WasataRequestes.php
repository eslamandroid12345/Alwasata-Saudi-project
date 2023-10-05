<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasataRequestes extends Model
{
    protected $table='wasata_requestes';
    protected $guarded=[];

    public function externalCustomer()
    {
        return $this->belongsTo(ExternalCustomer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
