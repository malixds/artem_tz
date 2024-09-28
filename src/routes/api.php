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
Route::put('/password/{uuid}', [UserController::class, 'password'])->name('password');

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/users/{uuid?}', [UserController::class, 'users'])->name('users');
    Route::put('/update/{uuid}', [UserController::class, 'update'])->name('update');

    Route::delete('/cart/{uuid}', [UserController::class, 'cart'])->name('cart-delete');
    Route::get('/cart/{uuid?}', [CartController::class, 'cart'])->name('cart');

    Route::post('/recover/{uuid}', [UserController::class, 'recover'])->name('recover');
    Route::post('/recoverGroup/{uuids}', [UserController::class, 'recoverGroup'])->name('recover-group');

    Route::delete('/delete/{uuid}', [UserController::class, 'delete'])->name('delete');
    Route::delete('/deleteGroup', [UserController::class, 'deleteGroup'])->name('delete-group');

    Route::delete('/cartGroup', [UserController::class, 'cartGroup'])->name('cart-group');

});


