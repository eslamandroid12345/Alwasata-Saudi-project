<?php
namespace App\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class NotificationScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('receiver_type', '=','web')
            ->where('request_type','=' ,1)
            ->whereDate('reminder_date', '>', Carbon::now()->subDays(30))
            ->whereNotNull('reminder_date');
    }
}
