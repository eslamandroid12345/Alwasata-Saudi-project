<?php

namespace App;

use App\Traits\HasMobileAttribute;
use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class customer extends Model implements AuthenticatableContract
{
    use HasMobileAttribute;
    use HasApiTokens, Notifiable;
    use Authenticatable;

    protected $guard = 'customer';

    protected $fillable = [
        'name',
        'sms_count',
        'send_email_count',
        'username',
        'email',
        'pass_text',
        'mobile',
        'sex',
        'salary',
        'age',
        'work',
        'birth_date_higri',
        'askary_id',
        'madany_id',
        'military_rank',
        'job_title',
        'salary_id',
        'is_supported',
        'user_id',
        'has_joint',
        'has_obligations',
        'obligations_value',
        'has_financial_distress',
        'financial_distress_value',
        'message_status',
        'isVerified',
    ];
    protected $hidden = ['password','remember_token',];
    public static function getCustomerByID($id)
    {
        $customer = self::whereId($id)->first();
        if ($customer) {
            return $customer = ['id' => $id, 'name' => $customer->name, 'mobile' => $customer->mobile];
        }
        else {
            return $customer = ['id' => $id, 'name' => 'العميل غير موجود', 'mobile' => ''];
        }
    }
    public function request() : HasOne
    {
        return $this->hasOne(\App\Models\Request::class,'customer_id');
    }
    public static function customerRequest($custID)
    {
        return DB::table('requests')->where('customer_id', $custID)
            ->first();
    }

    public static function lastMessage($sender)
    {
        $query = Message::where('to', Auth::id())->where('from', $sender)->latest('created_at')->first();

        if ($query) {
            return $query;
        }
        else {
            return '';
        }
    }

    public function phones()
    {
        return $this->belongsTo('App\CustomersPhone');
    }

    public function requests()
    {
        return $this->hasMany(request::class);
    }

    public function joint()
    {
        return $this->hasOne(joint::class);
    }

    public function real_estat()
    {
        return $this->hasOne(real_estat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setAskaryWorkAttribute($value)
    {
        $this->attributes['askary_id'] = $value;
    }

    public function setMadanyWorkAttribute($value)
    {
        $this->attributes['madany_id'] = $value;
    }

    public function setMiliratyRankAttribute($value)
    {
        $this->attributes['military_rank'] = $value;
    }

    public function setUserAttribute($value)
    {
        $this->attributes['user_id'] = $value;
    }

    public function madanyWork($work)
    {

        $checkWork = DB::table('madany_works')->where('value', $work)->first();

        if (!empty($checkWork)) {
            return $checkWork->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('madany_works')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $work,
                ]
            );

            return $resultId;

        }
    }

    public function askaryWork($work)
    {

        $checkWork = DB::table('military_ranks')->where('value', $work)->first();

        if (!empty($checkWork)) {
            return $checkWork->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('military_ranks')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $work,
                ]
            );

            return $resultId;

        }
    }

    public function salaryBank($bank)
    {

        if ($bank == 'مصرف الراجحي') {
            $bank = 'بنك الراجحي';
        }

        $checkBank = DB::table('salary_sources')->where('value', $bank)->first();

        if (!empty($checkBank)) {
            return $checkBank->id;
        } // just get id of madany works

        else { // we have to add a new one and get the id :)

            $resultId = DB::table('salary_sources')->insertGetId(
                [ //add it once use insertGetId
                  'value' => $bank,
                ]
            );

            return $resultId;

        }
    }

    public function to_messages()
    {
        return $this->morphMany(Message::class, 'to', 'to_type', 'to');
    }

    public function getUnreadAttribute()
    {
        $user_type = auth('customer')->user() ? 'App\customer' : 'App\User';
        return $this->from_messages()->where('to', auth()->id())
            ->where('to_type', $user_type)
            ->where('is_read', false)->count();
    }

    public function from_messages()
    {
        return $this->morphMany(Message::class, 'from', 'from_type', 'from');
    }

    //---------------------------------------------
    //Get All Customer Reminders
    //---------------------------------------------

    public function reminders()
    {
        return $this->hasMany('App\Model\Reminder');
    }

}
