<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\HasMany;

use App\WaitingRequestNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyWaitingRequestNotification
{

    /**
     * @return HasMany
     */
    public function waitingRequestNotifications(): HasMany
    {
        return $this->hasMany(WaitingRequestNotification::class);
    }
}
