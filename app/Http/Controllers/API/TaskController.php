<?php

namespace App\Http\Controllers\API;

use App\Enum\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Transformers\TaskTransformer;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = Task::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
            $tasks->transform(function ($item) {
                return (new TaskTransformer)->transform($item);
            });

            return $this->successResponse($tasks, 'Task list retrieved successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
                'todo_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Invalid task data.', StatusEnum::BAD_REQUEST);
            }
            
            $request->merge(['user_id' => Auth::id()]);
            $task = Task::create($request->all());
            
            return $this->successResponse((new TaskTransformer)->transform($task), 'Task created successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Invalid task data.', StatusEnum::BAD_REQUEST);
            }

            $task = Task::findOrFail($id);
            $task->update($request->all());
            
            return $this->successResponse((new TaskTransformer)->transform($task), 'Task updated successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            
            return $this->successResponse(null, 'Task deleted successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }
}
