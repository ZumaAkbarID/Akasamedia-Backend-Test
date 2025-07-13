<?php

use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\NilaiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->middleware('guest');

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/divisions', [DivisionController::class, 'getAllDivisions']);

  Route::resource('employees', EmployeeController::class)
    ->only(['index', 'store', 'update', 'destroy']);

  Route::post('/logout', LogoutController::class);
});

Route::get('/nilai-rt', [NilaiController::class, 'nilaiRt']);
Route::get('/nilai-st', [NilaiController::class, 'nilaiSt']);
