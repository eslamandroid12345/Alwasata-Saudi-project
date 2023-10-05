<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToClassification;
use App\Traits\BelongsTo\BelongsToRequestCondition;

/**
 *
 */
class ClassificationCondition extends BaseModel
{

    use BelongsToRequestCondition;
    use BelongsToClassification;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'class_conditions';
    /**
     * The attributes that are mass assignable.
     * cond_type: 0: for quality conditions. 1: for waiting requests condition
     * @var array
     */
    protected $fillable = [
        'cond_id',
        'class_id',
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
     * @return string
     */
    public function belongsToClassificationForeignKey(): string
    {
        return 'class_id';
    }
}
