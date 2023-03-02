<?php

use App\Http\Controllers\API as BaseApiDir;
use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [BaseApiDir\AuthController::class, 'login']);
Route::post('register', [BaseApiDir\AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return (new ApiController)
        ->successResponse($request->user(), 'User information retrieved successfully.', 200);
});
