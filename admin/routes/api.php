<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\LoginController::class, 'apiLogin']);
Route::post('/logout', [\App\Http\Controllers\LoginController::class, 'apiLogout']);
Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'apiRegister']);
Route::post('/register-driver', [\App\Http\Controllers\RegisterController::class, 'apiRegisterDriver']);
Route::post('/register-driver-docs', [\App\Http\Controllers\RegisterController::class, 'apiRegisterDriverDocs']); 