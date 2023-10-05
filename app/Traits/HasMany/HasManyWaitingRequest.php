<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\WaitingRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyWaitingRequest
{

    /**
     * @return HasMany
     */
    public function waitingRequests(): HasMany
    {
        return $this->hasMany(WaitingRequest::class);
    }
}
