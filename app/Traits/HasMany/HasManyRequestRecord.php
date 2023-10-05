<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\RequestRecord as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyRequestRecord
{

    /**
     * @return HasMany
     */
    public function requestRecords(): HasMany
    {
        return $this->hasMany(Model::class, $this->hasManyRequestRecordForeignKey());
    }

    /**
     * @return string
     */
    public function hasManyRequestRecordForeignKey(): string
    {
        return (new static)->getForeignKey();
    }
}
