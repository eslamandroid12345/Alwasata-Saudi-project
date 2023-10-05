<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits;

use App\Models\UserToken;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPushTokensTrait
{
    /**
     * Get all device push token as array
     *
     * @return array
     */
    public function getPushTokens(): array
    {
        return array_values(array_filter($this->pushTokens()->pluck('token')->toArray()));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function pushTokens(): MorphMany
    {
        return $this->morphMany(UserToken::class, 'tokenable');
    }
}
