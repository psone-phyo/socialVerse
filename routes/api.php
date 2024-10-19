<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;

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

//not used
Route::group(['prefix' => 'address'], function(){
    // (https://countriesnow.space/api/v0.1/countries) -> get all countries from postman api (get method)
    // (https://countriesnow.space/api/v0.1/countries/cities) -> get cities by country (post method)

    //country
    // Route::get('/countries/{id?}', [CountryController::class, 'get']);
    // Route::group(['prefix' => 'country'], function(){
    //     Route::post('create', [CountryController::class, 'store']);
    //     Route::delete('delete/{id?}', [CountryController::class, 'delete']);
    //     Route::put('update', [CountryController::class, 'update']);
    // });
});

//user info
Route::get('/users/{id?}', [UserController::class, 'get']);
Route::group(['prefix' => 'user'], function(){
    Route::post('/create', [UserController::class, 'create']);
    Route::delete('/delete/{id?}', [UserController::class, 'delete']);
    Route::put('/update', [UserController::class, 'update']);
    Route::put('/update/name', [UserController::class, 'updateName']);
    Route::put('/update/email', [UserController::class, 'updateEmail']);
    Route::put('/update/password', [UserController::class, 'changePassword']);

});

//user address
// Route::group(['prefix' => 'address'], function(){
//     Route::get('/get/{id?}', [CountryController::class, 'get']);
//     Route::delete('/delete/{id?}', [CountryController::class, 'delete']);
//     Route::put('/update', [CountryController::class, 'update']);
//     Route::post('/create', [CountryController::class, 'create']);
// });

//company
Route::get('/companies/{id?}', [CompanyController::class, 'get']);
Route::group(['prefix' => 'company'], function(){
    Route::post('/create', [CompanyController::class, 'store']);
    Route::delete('/delete/{id?}', [CompanyController::class, 'delete']);
    Route::put('/update', [CompanyController::class, 'update']);
});

//job
Route::get('/jobs/{id?}', [WorkController::class, 'get']);
Route::group(['prefix' => 'job'], function(){
    Route::post('/create', [WorkController::class, 'store']);
    Route::delete('/delete/{id?}', [WorkController::class, 'delete']);
    Route::put('/update', [WorkController::class, 'update']);
});
