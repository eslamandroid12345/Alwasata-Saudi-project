<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\ClassificationAlertSchedule as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyClassificationAlertSchedule
{

    /**
     * @return HasMany
     */
    public function classificationAlertSchedules(): HasMany
    {
        return $this->hasMany(Model::class);
    }
}
