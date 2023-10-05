<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToClassification;
use App\Traits\BelongsTo\BelongsToRequest;
use App\Traits\BelongsTo\BelongsToUser;

class ClassificationQuestionnaire extends BaseModel
{
    use BelongsToUser;
    use BelongsToRequest;
    use BelongsToClassification;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'classification_id',
        'request_id',
        'title',
        'body',
        'value',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'user_id'           => null,
        'classification_id' => null,
        'request_id'        => null,
        'title'             => null,
        'body'              => null,
        'value'             => null,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'bool',
    ];

}
