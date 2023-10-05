<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequestCondition;
use App\Traits\BelongsTo\BelongsToUser;

/**
 *
 */
class UserCondition extends BaseModel
{
    use BelongsToRequestCondition;
    use BelongsToUser;

    /**
     * The attributes that are mass assignable.
     * cond_type: 0: for quality conditions. 1: for waiting requests condition
     * @var array
     */
    protected $fillable = [
        'cond_id',
        'user_id',
        'cond_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cond_type' => 'int',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'cond_type' => 0,
    ];
}
