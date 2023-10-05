<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequest;
use App\Traits\BelongsTo\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class QualityRequest extends BaseModel
{
    use BelongsToRequest;
    use BelongsToUser;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quality_reqs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'allow_recive',
        'user_id',
        'req_id',
        'con_id',
        'status',
        'is_followed',
        'req_class_id_agent',
        'updated_at',
    ];

    /**
     * @return string
     */
    public function belongsToRequestForeignKey(): string
    {
        return 'req_id';
    }

    /**
     * @param  Builder  $builder
     * @param  string|string[]  $class_id
     * @return Builder
     */
    public function scopeByClassification(Builder $builder, $class_id): Builder
    {
        $class_id instanceof Model && ($class_id = [$class_id->id]);
        !is_array($class_id) && ($class_id = explode(',', $class_id));
        return $builder->whereHas('request', fn(Builder $b) => $b->whereIn('class_id_quality', $class_id));
    }

    /**
     * @param  Builder  $builder
     * @param  string|Carbon  $date
     * @return Builder
     */
    public function scopeByFromRequestDate(Builder $builder, $date): Builder
    {
        return $builder->whereHas('request', fn(Builder $b) => $b->whereDate('req_date', '>=', Carbon::make($date)));
    }

    /**
     * @param  Builder  $builder
     * @param  string|Carbon  $date
     * @return Builder
     */
    public function scopeByToRequestDate(Builder $builder, $date): Builder
    {
        return $builder->whereHas('request', fn(Builder $b) => $b->whereDate('req_date', '<=', Carbon::make($date)));
    }

    /**
     * @param  Builder  $builder
     * @param  string|int|User  $user
     * @param  string|string[]|int|int[]  $status
     * @param  string|string[]|int|int[]  $class_id
     * @param  string|string[]|int|int[]  $baskets
     * @param  string|Carbon  $from
     * @param  string|Carbon  $to
     * @return Builder
     */
    public function scopeByReport5(Builder $builder, $user = null, $status = null, $class_id = null, $baskets =  null, $from = null, $to = null): Builder
    {
        $builder->hasData();
        $from && $builder->byFromRequestDate($from);
        $to && $builder->byToRequestDate($to);

        if (!is_null($class_id)) {
            !is_array($class_id) && ($class_id = explode(',', $class_id));
            (is_array($class_id) && !empty($class_id)) && $builder->byClassification($class_id);

        }

        if (!is_null($status)) {
            !is_array($status) && ($status = explode(',', $status));
            (is_array($status) && !empty($status)) && $builder->whereIn('quality_reqs.status', $status);
        }

        if (!is_null($baskets)) {
            //dd($baskets);
            !is_array($baskets) && ($baskets = explode(',', $baskets));
            (is_array($baskets) && !empty($baskets)) && $builder->where(function (Builder $builder) use ($baskets) {
                if (in_array('received', $baskets)) {
                    $builder->orWhere(fn($q) => $q->receivedBasket());
                }

                if (in_array('follow', $baskets)) {
                    $builder->orWhere(fn($q) => $q->followBasket());
                }

                if (in_array('archived', $baskets)) {
                    $builder->orWhere(fn($q) => $q->archivedBasket());
                }

                if (in_array('finished', $baskets)) {
                    $builder->orWhere(fn($q) => $q->finishedBasket());
                }

                return $builder;
            });
        }

        if ($user) {
            $user instanceof Model && ($user = $user->id);
            $builder->where('quality_reqs.user_id', $user);
        }
        //dd($builder->toSql());
        return $builder;
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeHasData(Builder $builder): Builder
    {
        return $builder
            ->has('request')
            ->has('request.customer')
            ->has('request.user');

    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeReceivedBasket(Builder $builder): Builder
    {
        return $builder->where(fn(Builder $q) => $q->hasData()->where('quality_reqs.allow_recive', 1)->whereIn('quality_reqs.status', [0, 1, 2])->where('quality_reqs.is_followed', 0));
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeFollowBasket(Builder $builder): Builder
    {
        return $builder->where(fn(Builder $q) => $q->hasData()
            ->where('quality_reqs.allow_recive', 1)
            ->whereIn('quality_reqs.status', [0, 1, 2])
            ->where('quality_reqs.is_followed', 1));
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeArchivedBasket(Builder $builder): Builder
    {
        return $builder->where(fn(Builder $q) => $q->hasData()->where('quality_reqs.allow_recive', 1)->where('quality_reqs.status', 5));
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeFinishedBasket(Builder $builder): Builder
    {
        return $builder->where(fn(Builder $q) => $q->hasData()->where('quality_reqs.allow_recive', 1)->where('quality_reqs.status', 3));
    }
}
