<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class RequestRecord extends BaseModel
{
    use BelongsToUser;

    /**
     * Used in 'colum', this is for agent type classification
     * @var string
     */
    const AGENT_CLASS_RECORD = 'class_agent';

    /**
     * Used in 'colum', this is for quality type classification
     * @var string
     */
    const QUALITY_CLASS_RECORD = 'class_quality';

    /**
     * Used in 'colum'
     * @var string
     */
    const COMMENT_RECORD = 'comment';

    /**
     * Used in 'comment'
     * @var string
     */
    const AUTO_COMMENT = 'تلقائي';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'req_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'colum',
        'user_id',
        'user_switch_id',
        'req_id',
        'updateValue_at',
        'value',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updateValue_at' => 'datetime',
    ];

    public function agentClassification(): BelongsTo
    {
        return $this->belongsTo(Classification::class, 'value')->withDefault();
    }

    public function scopeQualityClassRecords(Builder $builder, $value, $user = null, $from = null, $to = null)
    {
        if ($user) {
            $user instanceof Model && ($user = $user->id);
            $builder->where('user_id', $user);
        }
        $from && $builder->whereDate('updateValue_at', '>=', Carbon::make($from));
        $to && $builder->whereDate('updateValue_at', '<=', Carbon::make($to));

        return $builder->where([
            'colum' => RequestRecord::QUALITY_CLASS_RECORD,
            'value' => $value,
        ]);
    }
}
