<?php

use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\FoodController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
    
   
});

 Route::group(['middleware' => 'jwt.auth'], function(){ 
    Route::get('/get-categories', [CategoryController::class, 'index']);   
    Route::post('/category-store', [CategoryController::class, 'store']);
    Route::get('/edit-category/{category}', [CategoryController::class, 'edit']);
    Route::post('/category-store', [CategoryController::class, 'store']);
    Route::post('/category-update/{category}', [CategoryController::class, 'update']);

    Route::get('/food-create', [FoodController::class, 'create']);
    Route::apiResource('foods', FoodController::class);
    Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy']);
});


    Route::get('/category-foods',[FoodController::class,'categoryFoods']);

Route::get('/change-lang', [LanguageController::class, 'changeLang']);
Route::get('/get-locale', [LanguageController::class, 'getLocale']);
Route::get('/welcome-message', [LanguageController::class, 'welcomeMessage']);
Route::get('/languages', [LanguageController::class, 'language']);


