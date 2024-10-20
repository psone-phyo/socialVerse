<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

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

//friend request
Route::group(['prefix' => 'friend'], function(){
    Route::post('/add-friend', [FriendshipController::class, 'addFriend']);
    Route::post('/accept-friend', [FriendshipController::class, 'acceptFriend']);
    Route::delete('/reject-request', [FriendshipController::class, 'rejectFriend']);
    Route::delete('/unfriend', [FriendshipController::class, 'unfriend']);
});

//notification
Route::group(['prefix' => 'notification'], function(){
    Route::post('/seen', [NotificationController::class, 'readNotification']);
    Route::get('/get/{user_id}', [NotificationController::class, 'get']);
});

//post
Route::group(['prefix' => 'post'], function(){
    Route::post('/create', [PostController::class, 'store']);
    Route::put('/update', [PostController::class, 'update']);
    Route::get('/get/{user_id}', [PostController::class, 'get']);
    Route::delete('/delete/{id}', [PostController::class, 'delete']);
    // Route::post('/image/store', [ImageController::class, 'storeImage']);

    //like
    Route::post('/like', [LikeController::class, 'like']);
    Route::post('/dislike', [LikeController::class, 'dislike']);

    //comment
    Route::group(['prefix' => 'comment'], function(){
        Route::post('/create', [CommentController::class, 'create']);
        Route::get('/get/{post_id}', [CommentController::class, 'get']);
        Route::put('/update', [CommentController::class, 'update']);
        Route::delete('/delete/{comment_id}', [CommentController::class, 'delete']);
    });
});
