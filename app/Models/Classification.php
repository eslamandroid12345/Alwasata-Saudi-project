<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\HasMany\HasManyClassificationAlertSetting;
use Illuminate\Database\Eloquent\Builder;

class Classification extends BaseModel
{
    /**
     * ID of row in db
     * @var int
     */
    const TEST_CLASSIFICATION = 72;
    const AGENT_UNABLE_TO_COMMUNICATE = 33;

    /**
     * ID of row in db
     * @var int
     */
    const AGENT_NOT_ANSWER = 61;
    /**
     * ID of row in db
     * @var int
     */
    const POSTPONED_COMMUNICATION = 62;

    use HasManyClassificationAlertSetting;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classifcations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'status',
        'user_role',
        'type',
        'is_required_in_calculater',
    ];

    public function requests()
    {
        return $this->hasMany("App\Models\Request",'id','class_id_agent');
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type'                      => 'int',
        'is_required_in_calculater' => 'bool',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'value'                     => null,
        'status'                    => 0,
        'user_role'                 => 0,
        'type'                      => null,
        'is_required_in_calculater' => null,
    ];

    /**
     * @return bool
     */
    public function isNegative(): bool
    {
        return !$this->isPositive();
    }

    /**
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->type == 1;
    }

    /**
     * name of attribute will display tne model name Like created_at
     *
     * @return string
     */
    public function getNameColumn(): string
    {
        return 'value';
    }
    public function scopeDateFilter(Builder $builder,$start,$end):Builder
    {
        return $builder->when($start && !$end, function ($q, $v) use($start){
            $q->where('created_at','>=', $start);
        })->when($end && !$start, function ($q, $v) use($end){
            $q->where('created_at','<=' ,$end);
        })->when($end && $start, function ($q, $v) use($end,$start){
            $q->whereBetween('created_at', [$start, $end]);
        });
    }

}
