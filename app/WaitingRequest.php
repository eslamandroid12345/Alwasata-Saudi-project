<?php

namespace App;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequest;
use App\Traits\BelongsTo\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;

class WaitingRequest extends BaseModel
{
    use BelongsToRequest;
    use BelongsToUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'user_id',
        'message_at',
        'replay_at',
        'message_type',
        'message_value',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'message_at' => 'datetime',
        'replay_at'  => 'datetime',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'user_id'   => null,
        'replay_at' => null,
    ];

    public function scopeWaitingOnly(Builder $builder)
    {
        return $builder->whereNull('replay_at')->whereNull('user_id');
    }

    public function scopeWaitingList(Builder $builder, $minutes = null)
    {
        if (is_null($minutes)) {
            $settingTimePerMinutes = Setting::where('option_name', 'waitingRequest_replaytime')->first();
            $minutes = (int) ($settingTimePerMinutes ? $settingTimePerMinutes->option_value : 0);
        }
        $sentAt = now()->subMinutes($minutes);
        return $builder->whereHas('request', fn(Builder $r) => $r->where('statusReq', 0))->where(fn(Builder $builder) => $builder->waitingOnly()->where('message_at', '<=', $sentAt));
    }
}
