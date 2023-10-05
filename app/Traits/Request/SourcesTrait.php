<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\Request;

trait SourcesTrait
{
    public function getSourceToStringAttribute($value)
    {
        return $value ?: $this->getSourceToString();
    }

    public function getSourceToString(): string
    {
        $source = $this->requestSource->value ?: $this->source;
        $source .= " - {$this->collaborator->name}";
        return trim($source, ' -');
    }
}
