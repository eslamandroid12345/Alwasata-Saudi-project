<?php

namespace App;

use App\Models\Customer;
use App\Models\Request;
use App\Models\User;
use Google\Cloud\Monitoring\V3\Service\Custom;
use Illuminate\Database\Eloquent\Model;

class RequestNeedAction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'request_need_actions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action',
        'agent_id',
        'customer_id',
        'req_id',
        'status',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 0,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class,'req_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'agent_id');
    }
}
