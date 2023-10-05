<?php

namespace App\Model;

use App\customer;
use App\joint;
use App\Models\RealEstate;
use Illuminate\Database\Eloquent\Model;

class PendingRequest extends Model
{

    protected $guarded = [];
    protected $table = 'pending_requests';

    public function customer()
    {
        return $this->belongsTo(customer::class);
    }

    public function realEstate()
    {
        return $this->belongsTo(RealEstate::class, 'real_id');
    }

    public function joint()
    {
        return $this->belongsTo(joint::class, 'joint_id');
    }
}


