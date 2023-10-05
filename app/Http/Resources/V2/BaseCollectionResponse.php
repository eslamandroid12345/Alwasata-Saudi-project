<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollectionResponse extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = 'App\Http\Resources\BaseResource';

    /**
     * ApiCollectionResponse constructor.
     *
     * @param $resource
     * @param  null|string  $collects
     */
    public function __construct($resource, $collects = null)
    {
        $this->collects = $collects ? $collects : $this->collects;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'message' => "",
            'data'    => $this->collection,
            'success' => true,
        ];
    }
}
