<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tasks', TaskController::class);
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
