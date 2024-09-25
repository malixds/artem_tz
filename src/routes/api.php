<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::put('/password/{id}', [UserController::class, 'password'])->name('password');

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/users/{id?}', [UserController::class, 'users'])->name('users');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('update');

    Route::delete('/cart/{id}', [UserController::class, 'cart'])->name('cart');
    Route::get('/cart/{id}', [CartController::class, 'cart'])->name('cart');

    Route::post('/recover/{id}', [UserController::class, 'recover'])->name('recover');
    Route::post('/recoverGroup/{ids}', [UserController::class, 'recoverGroup'])->name('recover-group');

    Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
    Route::delete('/deleteGroup/{ids}', [UserController::class, 'deleteGroup'])->name('delete-group');

    Route::delete('/cart/group/{ids}', [UserController::class, 'cartGroup'])->name('cart-group');

});


