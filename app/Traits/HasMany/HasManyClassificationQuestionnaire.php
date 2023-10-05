<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\ClassificationQuestionnaire as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyClassificationQuestionnaire
{

    /**
     * @return HasMany
     */
    public function classificationQuestionnaires(): HasMany
    {
        return $this->hasMany(Model::class);
    }
}
