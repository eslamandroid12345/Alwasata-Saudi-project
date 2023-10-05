<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundingYear extends Model
{
    //*************************************************
    // Task-22 Fillable instead of $guards [because API Added Fileds]
    //*************************************************
    protected $fillable=['apiId', 'value', 'key', 'text', 'bank_id', 'bank_code',
        'bank_id_to_string', 'residential_support', 'residential_support_api', 'after_retirement',
        'personal', 'personal_api', 'extended', 'extended_api', 'guarantees', 'guarantees_api',
        'status', 'job_position_id', 'job_position_id_api', 'job_position_code',
        'job_position_code_api', 'job_position_id_to_string', 'job_position_id_to_string_api',
        'residential_support_to_string', 'residential_support_to_string_api', 'extended_to_string',
        'extended_to_string_api', 'personal_to_string', 'personal_to_string_api',
        'guarantees_to_string', 'guarantees_to_string_api', 'years_to_string', 'years_to_string_api',
        'years', 'years_api', 'after_retirement_to_string', 'user_id'];

    public function suggests()
    {
        return $this->morphMany(SuggestionUser::class, 'suggestable');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
