<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToClassification;

class ClassificationAlertSetting extends BaseModel
{
    use BelongsToClassification;

    /** @var string[] Alert types */
    const TYPES = [
        'email'          => 'mail',
        'sms'            => 'sms',
        'push_token'     => 'push_token',
        'move_to_freeze' => 'move_to_freeze',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'classification_id',
        'step',
        'hours_to_send',
        'type',
    ];
}
