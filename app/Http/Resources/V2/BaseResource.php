<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BaseResource extends JsonResource implements BaseResourceInterface
{
    /**
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }
        if (is_array($this->resource)) {
            return $this->resource;
        }
        if (method_exists($this, 'transformer')) {
            return $this->transformer($request);
        }

        $id = $this->resource->id;
        $name = $this->resource->name;
        return array_merge($this->resource->toArray(), [
            "id"    => $id,
            "value" => $id,
            "key"   => (string) $id,
            "text"  => (string) $name,
        ]);
    }

    /**
     * Transform model data
     *
     * @param  Request|null  $request
     * @return array
     */
    public function transformer(?Request $request = null): array
    {
        return [];
    }

    /**
     * @param  array  $merge
     *
     * @return array
     */
    protected function transformModel(array $merge = []): array
    {
        $model = $this->resource;
        $id = $model->id;
        $name = $model->name;

        return array_merge([
            "id"    => $id,
            "value" => $id,
            "key"   => (string) $id,
            "text"  => $name,
            "name"  => $name,
        ], Arr::except($model->only($model->getFillable()), $model->getHidden()), $merge);
    }
}
