<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;

// Route::get('/user', function (Request $request) {
//     return "Hello, The API is working!";
// });

 
Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});