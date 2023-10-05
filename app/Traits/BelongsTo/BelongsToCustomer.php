<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\BelongsTo;

use App\Models\Customer as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCustomer
{
    protected static function bootBelongsToCustomer()
    {

    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Model::class,'customer_id')->withDefault();
    }
}
