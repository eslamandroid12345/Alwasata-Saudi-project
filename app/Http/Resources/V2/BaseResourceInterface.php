<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;

interface BaseResourceInterface
{
    /**
     * Transform model data
     *
     * @param  Request|null  $request
     * @return array
     */
    public function transformer(?Request $request = null): array;
}
