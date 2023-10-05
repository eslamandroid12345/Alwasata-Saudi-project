<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class helpDesk extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'descrebtion',
        'replay',
        'status',
        'customer_id',
        'user_id',
        'date_replay',
        'technical_owner_id',
        'parent_id',
        'msg_type',
    ];

    protected $table = 'help_desks';

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class)->withDefault();
    }
    public function User()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function Image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
