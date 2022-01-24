<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Custom Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

//Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


//Private Routes
Route::middleware('auth:sanctum')->group(function () {    
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('auth-user', UserController::class);
    Route::put('auth-user/username/{id}', [UserController::class, 'updateUsername']);
});
 