<?php

namespace App\Http\Transformers;

use App\Http\Transformers\Transformer;
use Illuminate\Support\Collection;

class UserTransformer extends Transformer
{
    public function transform($item)
    {
        $data = [
            'name' => $item->name ?? null,
            'email' => $item->email ?? null,
            'access_token' => $item->access_token ?? null,
        ];

        return new Collection($data);
    }
}