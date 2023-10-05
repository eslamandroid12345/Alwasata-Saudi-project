<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\Models\CustomerPhone;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyCustomerPhone
{

    /**
     * @return HasMany
     */
    public function customerPhones(): HasMany
    {
        return $this->hasMany(CustomerPhone::class);
    }
}
