<?php

namespace App\Http\Transformers;

use App\Http\Transformers\Transformer;
use Illuminate\Support\Collection;
use App\Http\Transformers\TodoTransformer;

class TaskTransformer extends Transformer
{
    public function transform($item, $parentTodo = true)
    {
        $data = [
            'id' => $item->id ?? null,
            'name' => $item->name ?? null,
            'status' => $item->status ?? null,
            'created_at' => $item->created_at ? $item->created_at->diffForHumans() : null,
            'updated_at' => $item->updated_at ? $item->updated_at->diffForHumans() : null,
        ];
        
        if ($parentTodo) {
            $data['todo'] = (new TodoTransformer)->transform($item->todo()->first(), false);
        }

        return new Collection($data);
    }
}