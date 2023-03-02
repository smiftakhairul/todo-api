<?php

namespace App\Http\Controllers\API;

use App\Enum\StatusEnum;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{    
    /**
     * successResponse
     *
     * @param  mixed $data
     * @param  mixed $message
     * @param  mixed $code
     * @return void
     */
    public function successResponse($data = [], $message = null, $code = StatusEnum::OK)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $code);
    }
    
    /**
     * errorResponse
     *
     * @param  mixed $message
     * @param  mixed $code
     * @return void
     */
    public function errorResponse($message = null, $code = StatusEnum::BAD_REQUEST)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ], $code);
    }
    
    /**
     * successMessage
     *
     * @param  mixed $message
     * @param  mixed $code
     * @return void
     */
    public function successMessage($message = null, $code = StatusEnum::OK)
    {
        return [
            'status' => 'success',
            'message' => $message,
            'code' => $code
        ];
    }
    
    /**
     * errorMessage
     *
     * @param  mixed $message
     * @param  mixed $code
     * @return void
     */
    public function errorMessage($message = null, $code = StatusEnum::BAD_REQUEST)
    {
        return [
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ];
    }
}
