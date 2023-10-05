<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\ClassificationAlertSetting as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyClassificationAlertSetting
{

    /**
     * @return HasMany
     */
    public function classificationAlertSettings(): HasMany
    {
        return $this->hasMany(Model::class);
    }
}
