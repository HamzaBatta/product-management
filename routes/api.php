<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//Route::post('products/create' , [ProductController::class , 'create'])->name('products.store');
Route::resource('products' , ProductController::class)->except('create');


Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::middleware('auth:sanctum')->group(function(){
       Route::post('logout' , 'logout');
    });
});

Route::resource('products.images' , ImageController::class)
        ->except('update','edit','show','create');
