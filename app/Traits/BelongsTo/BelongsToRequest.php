<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\BelongsTo;

use App\Models\Request;
use App\Models\Request as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToRequest
{
    protected static function bootBelongsToRequest()
    {

    }

    /**
     * @return BelongsTo
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Model::class, $this->belongsToRequestForeignKey())->withDefault();
    }

    /**
     * @return string
     */
    public function belongsToRequestForeignKey(): string
    {
        return (new Request())->getForeignKey();
    }
}
