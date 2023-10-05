<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\QualityRequest as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyQualityRequest
{

    /**
     * @return HasMany
     */
    public function qualityRequests(): HasMany
    {
        return $this->hasMany(Model::class, $this->hasManyQualityRequestForeignKey());
    }

    /**
     * @return string
     */
    public function hasManyQualityRequestForeignKey(): string
    {
        return (new static)->getForeignKey();
    }
}
