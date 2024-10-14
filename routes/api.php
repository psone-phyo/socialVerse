<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

//for school, university and college table in database
//education msut be schools or universities or colleges
Route::group(['prefix' => 'edu'], function(){
    Route::get('/{education}/{id?}', [EducationController::class, 'get']);
    Route::post('/{education}', [EducationController::class, 'store']);
    Route::delete('/{education}/{id?}', [EducationController::class, 'delete']);
    Route::put('/{education}', [EducationController::class, 'update']);
});
Route::get('/users/{id?}', [UserController::class, 'get']);

