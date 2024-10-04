<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('docs/api');
});

//Route::get('/tasks',[\App\Http\Controllers\TasksController::class,'tasks'])->name('tasks');
//
//// Admin Dashboard Route
//Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//
//// Users Route
//Route::get('/users', [UserController::class, 'index'])->name('users.index');
//
//// Settings Route
//Route::get('/settings', [SettingController::class, 'index'])->name('settings');
