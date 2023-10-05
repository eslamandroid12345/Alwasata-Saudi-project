<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequestCondition;

/**
 *
 */
class RequestStatusCondition extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'status_conditions';

    use BelongsToRequestCondition;

    /**
     * The attributes that are mass assignable.
     * cond_type: 0: for quality conditions. 1: for waiting requests condition
     * @var array
     */
    protected $fillable = [
        'status',
        'cond_id',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requestStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RequestStatus::class, 'status', 'status_id');
    }
}
