<?php

namespace App\Http\Transformers;

use App\Http\Transformers\TaskTransformer;
use App\Http\Transformers\Transformer;
use Illuminate\Support\Collection;

class TodoTransformer extends Transformer
{
    public function transform($item, $showTasks = true)
    {
        $data = [
            'id' => $item->id ?? null,
            'name' => $item->name ?? null,
            'status' => $item->status ?? null,
            'created_at' => $item->created_at ? $item->created_at->diffForHumans() : null,
            'updated_at' => $item->updated_at ? $item->updated_at->diffForHumans() : null,
        ];

        if ($showTasks) {
            $data['tasks'] = ($item->tasks()->get())->transform(function ($task) {
                return (new TaskTransformer)->transform($task, false);
            });
        }

        return new Collection($data);
    }
}