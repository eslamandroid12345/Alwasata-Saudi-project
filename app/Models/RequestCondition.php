<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;

/**
 *
 */
class RequestCondition extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timeDays',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timeDays' => 'int',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'timeDays' => 0,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classificationConditions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClassificationCondition::class, 'cond_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userConditions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserCondition::class, 'cond_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusConditions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RequestStatusCondition::class, 'cond_id');
    }

}
