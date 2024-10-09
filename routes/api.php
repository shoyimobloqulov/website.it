<?php

use App\Http\Controllers\Api\V1\CodeTestingController;
use App\Http\Controllers\Api\V1\CompilerController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\TasksController;
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
Route::get('/runtimes', [CompilerController::class, 'getRuntimes'])->middleware('auth:sanctum');
Route::post('/execute', [CompilerController::class, 'executeCode'])->middleware('auth:sanctum');
Route::get('/packages', [CompilerController::class, 'getPackages'])->middleware('auth:sanctum');
Route::post('/packages', [CompilerController::class, 'installPackage'])->middleware('auth:sanctum');
Route::delete('/packages', [CompilerController::class, 'deletePackage'])->middleware('auth:sanctum');

// Execute
Route::post('/execution-results', [CodeTestingController::class, 'storeExecutionResult'])->middleware('auth:sanctum');


// Tasks Tests
Route::post('/task/{task_id}/tests',[TasksController::class,'storeTestFile'])->middleware('auth:sanctum');
Route::put('/task/{task_id}/tests',[TasksController::class,'updateTestFile'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/user', [RegisterController::class,'getUserDetails']);
