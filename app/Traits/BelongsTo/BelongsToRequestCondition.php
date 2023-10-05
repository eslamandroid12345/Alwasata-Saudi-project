<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\BelongsTo;

use App\Models\RequestCondition as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToRequestCondition
{
    protected static function bootBelongsToRequestCondition()
    {

    }

    /**
     * @return BelongsTo
     */
    public function requestCondition(): BelongsTo
    {
        return $this->belongsTo(Model::class, $this->belongsToRequestForeignKey())->withDefault();
    }

    /**
     * @return string
     */
    public function belongsToRequestForeignKey(): string
    {
        return 'cond_id';
    }
}
