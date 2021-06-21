<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('users/store', [UserController::class, 'store']);
// Route::get('users', [UserController::class, 'index']);

Route::post('login', [UserController::class, 'login']);



Route::group(['middleware' => 'auth:api'], function (){
    Route::post('logout', [UserController::class, 'logout']);
    Route::apiResource('users', UserController::class)->except('store'); 
});
