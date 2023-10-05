<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\RequestJob as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyRequestJob
{

    /**
     * @return HasMany
     */
    public function requestJobs(): HasMany
    {
        return $this->hasMany(Model::class);
    }
}
