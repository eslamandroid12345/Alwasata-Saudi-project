<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\BelongsTo;

use App\Models\User as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    protected static function bootBelongsToUser()
    {

    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Model::class, $this->belongsToUserForeignKey())->withDefault();
    }

    /**
     * @return string
     */
    public function belongsToUserForeignKey(): string
    {
        return (new Model())->getForeignKey();
    }
}
