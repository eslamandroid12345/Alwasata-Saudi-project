<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\BelongsTo;

use App\Models\Classification as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToClassification
{
    protected static function bootBelongsToClassification()
    {

    }

    /**
     * @return BelongsTo
     */
    public function classification(): BelongsTo
    {
        return $this->belongsTo(Model::class)->withDefault();
    }

    /**
     * @return string
     */
    public function belongsToClassificationForeignKey(): string
    {
        return (new Model())->getForeignKey();
    }
}
