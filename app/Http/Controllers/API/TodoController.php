<?php

namespace App\Http\Controllers\API;

use App\Enum\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Transformers\TodoTransformer;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $todos = Todo::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
            $todos->transform(function ($item) {
                return (new TodoTransformer)->transform($item);
            });

            return $this->successResponse($todos, 'Todo list retrieved successfully.', StatusEnum::OK);
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
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Invalid todo data.', StatusEnum::BAD_REQUEST);
            }
            
            $request->merge(['user_id' => Auth::id()]);
            $todo = Todo::create($request->all());
            
            return $this->successResponse((new TodoTransformer)->transform($todo), 'Todo created successfully.', StatusEnum::OK);
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
                return $this->errorResponse('Invalid todo data.', StatusEnum::BAD_REQUEST);
            }

            $todo = Todo::findOrFail($id);
            $todo->update($request->all());
            
            return $this->successResponse((new TodoTransformer)->transform($todo), 'Todo updated successfully.', StatusEnum::OK);
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
            $todo = Todo::findOrFail($id);
            $todo->tasks()->delete();
            $todo->delete();
            
            return $this->successResponse(null, 'Todo deleted successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }
}
