<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => "api"], (function () {

    Route::prefix('/common')->group(function () {
        Route::get('/test', function () {
            return response()->json(["status" => true, "data" => null, "message" => "SmugLinks API Started"]);
        });
        Route::post('/signup', [AuthController::class, 'doSignup']);
        Route::post('/login', [AuthController::class, 'doLogin']);
        Route::get('/check-user-exists/{username}', [UserController::class, 'checkUserExists']);
    });

    Route::prefix('/user')->group(function () {
        Route::post('/update-yourself', [UserController::class, 'update']);
        Route::get('/details', [UserController::class, 'getUserDetails']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
        Route::post('/subscribe-to-plan',[SubscriptionController::class,'subscribePlan']);
        Route::post('/add-profile-image',[UserController::class, 'addProfileImage']);
        Route::get('/remove-profile-image',[UserController::class, 'deleteProfileImage']);
    });

    Route::prefix('/admin')->group(function (){
        Route::post('/create-subscription-plan',[SubscriptionController::class,'addPlan']);
    });

    Route::prefix('link')->group(function () {
        Route::post('/add', [LinkController::class, 'addLink']);
        Route::get('/get-all', [LinkController::class, 'getAllLinks']);
    });
    Route::prefix('subscription')->group(function (){
        Route::get('/get-all', [SubscriptionController::class, 'getAllSubscriptions']);
    });
}));
