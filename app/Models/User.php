<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\DailyPerformances;
use App\Traits\HasMany\HasManyWaitingRequestNotification;
use App\Traits\HasPushTokensTrait;
use App\Traits\User\UserAgentTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends BaseModel
{
    use HasPushTokensTrait;
    use UserAgentTrait;
    use HasManyWaitingRequestNotification;
    public function performances()
    {
        return $this->hasMany(DailyPerformances::class, 'user_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'locale',
        'username',
        'password',
        'role',
        'manager_id',
        'funding_manager_id',
        'mortgage_manager_id',
        'bank_id',
        'subdomain',
        'code',
        'name_for_admin',
        'req_count',
        'pen_count',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'name' => null,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    /**
     * @return HasMany
     */
    public function receivedRequestHistories(): HasMany
    {
        return $this->hasMany(RequestHistory::class, 'recive_id');
    }

    public function areas()
    {
        return $this->hasMany("App\CollaboratorProfile")
            ->where("key","area_id");
    }

    public function cities()
    {
        return $this->hasMany("App\CollaboratorProfile")
            ->where("key","city_id");
    }

    public function districts()
    {
        return $this->hasMany("App\CollaboratorProfile")
            ->where("key","district_id");
    }

    public function direction()
    {
        return $this->hasOne("App\CollaboratorProfile")
            ->where("key","direction");
    }
    public function quality_reqs()
    {
        return $this->hasMany("App\quality_req","user_id")
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id');
    }


    public function quality_reqs_followed()
    {
        return $this->quality_reqs()
            ->where('allow_recive', 1)
            ->whereIn('status', [0, 1, 2])
            ->where('is_followed', 1)
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ;
    }


    public function quality_reqs_completed()
    {
        return $this->quality_reqs()
            ->where('allow_recive', 1)
            ->where('status', 3)
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ;
    }


    public function quality_reqs_recevied(){
        return $this->quality_reqs()
            ->whereIn("status",[0, 1, 2])
            ->where('is_followed', 0)
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ;
    }
    public function quality_reqs_arch(){
        return $this->quality_reqs()
            ->where('allow_recive', 1)
            ->where('status', 5)
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ;
    }
}
