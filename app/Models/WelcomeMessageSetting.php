<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeMessageSetting extends Model
{
    protected $table = 'welcome_message_settings';
    protected $fillable = [
        //'request_source_id',
        'welcome_message',
        'time',
    ];

    public function requestSources(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(RequestSource::class, 'w_m_s_request_source');
    }

    public function classifications(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Classification::class, 'w_m_s_classification');
    }
}
