<?php

namespace App\Http\Resources\V2;

use App\Models\Classification as Model;
use Illuminate\Http\Request;

class ClassificationResource extends BaseResource
{
    /** @var Model */
    public $resource;

    /**
     * Transform model data
     *
     * @param  Request|null  $request
     * @return array
     */
    public function transformer(?Request $request = null): array
    {
        return $this->transformModel([
            'name'  => $this->resource->value,
            'text'  => $this->resource->text,
            'value' => $this->resource->id,
        ]);
    }
}
