<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankPercentage extends Model
{
    //*************************************************
    // Task-22 send only if user unauthrized
    //*************************************************
    protected $fillable=['apiId', 'value', 'key', 'text', 'bank_id', 'bank_code',
        'bank_id_to_string', 'personal', 'secured', 'secured_to_string', 'personal_api',
        'guarantees', 'guarantees_api', 'residential_support',
        'residential_support_api', 'residential_support_to_string', 'residential_support_to_string_api',
        'personal_to_string', 'personal_to_string_api', 'guarantees_to_string',
        'guarantees_to_string_api', 'from_year', 'from_year_api', 'to_year'
        , 'to_year_api', 'percentage', 'percentage_api', 'status', 'user_id'];

    public function suggests()
    {
        return $this->morphMany(SuggestionUser::class, 'suggestable');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
