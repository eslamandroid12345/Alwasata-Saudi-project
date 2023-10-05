<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\Request as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyRequest
{

    /**
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Model::class, $this->hasManyRequestForeignKey());
    }

    /**
     * @return string
     */
    public function hasManyRequestForeignKey(): string
    {
        return (new static)->getForeignKey();
    }
}
