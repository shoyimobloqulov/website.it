<?php

use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Api
Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::post('/logout', [RegisterController::class, 'logout'])->middleware('auth:sanctum');

//Tasks
Route::resource('task', TasksController::class, [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
