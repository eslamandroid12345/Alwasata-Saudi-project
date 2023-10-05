<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasOne;

use App\Models\QualityRequest as Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOneQualityRequest
{

    /**
     * @return HasOne
     */
    public function qualityRequest(): HasOne
    {
        return $this->hasOne(Model::class, $this->hasOneQualityRequestForeignKey());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualityRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
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

    /**
     * @return string
     */
    public function hasOneQualityRequestForeignKey(): string
    {
        return (new static)->getForeignKey();
    }
}
