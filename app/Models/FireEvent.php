<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FireEvent extends Model
{
    protected $table = 'fire_events';
    protected $fillable = [
        'customer_id', 'user_id', 'event_name', 'status', 'taggable_type', 'taggable_id'
    ];


    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
