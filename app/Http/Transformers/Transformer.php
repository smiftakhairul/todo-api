<?php

namespace App\Http\Transformers;

abstract class Transformer
{
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    public abstract function transform($item);

    public function isEmptyOrNull($value)
    {
        return (is_null($value) || empty($value));
    }
}