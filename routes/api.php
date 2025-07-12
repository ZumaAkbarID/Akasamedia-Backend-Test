<?php

use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->middleware('guest');

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/divisions', [DivisionController::class, 'getAllDivisions']);

  Route::resource('employees', EmployeeController::class)
    ->only(['index', 'store', 'update', 'destroy']);

  Route::post('/logout', LogoutController::class);
});
