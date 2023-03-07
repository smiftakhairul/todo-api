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

Route::name('api.')->group(function () {
    Route::post('login', [BaseApiDir\AuthController::class, 'login'])->name('login');
    Route::post('register', [BaseApiDir\AuthController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [BaseApiDir\AuthController::class, 'user'])->name('user'); 
        Route::post('logout', [BaseApiDir\AuthController::class, 'logout'])->name('logout');
        Route::apiResource('todos', BaseApiDir\TodoController::class)->except(['show']);
        Route::apiResource('tasks', BaseApiDir\TaskController::class)->except(['show']);
    });
});
