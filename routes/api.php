<?php

use App\Http\Controllers\Api\CompilerController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Api
Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::post('/logout', [RegisterController::class, 'logout'])->middleware('auth:sanctum');

// Tasks
Route::resource('task', TasksController::class, [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
])->middleware('auth:sanctum');

// Compiler
Route::get('/runtimes', [CompilerController::class, 'getRuntimes']);
Route::post('/execute', [CompilerController::class, 'executeCode']);
Route::get('/packages', [CompilerController::class, 'getPackages']);
Route::post('/packages', [CompilerController::class, 'installPackage']);
Route::delete('/packages', [CompilerController::class, 'deletePackage']);

// Tasks Tests
Route::post('/task/{task_id}/tests',[TasksController::class,'storeTestFile'])->middleware('auth:sanctum');
Route::put('/task/{task_id}/tests',[TasksController::class,'updateTestFile'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
