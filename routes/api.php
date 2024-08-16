<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => "api"], (function () {

    Route::prefix('/common')->group(function () {
        Route::get('/test', function () {
            return response()->json(["status" => true, "data" => null,"message"=> "SmugLinks API Started"]);
        });
        Route::post('/signup',[AuthController::class,'doSignup']);

        Route::get('/check-user-exists/{username}', [UserController::class, 'checkUserExists']);
    });

    Route::prefix('/user')->group(function () {
        Route::post('/login',[AuthController::class,'doLogin']);
        Route::post('/update-yourself',[UserController::class,'update']);
    });
    Route::prefix('link')->group(function (){
        Route::post('/add',[LinkController::class,'addLink']);
        Route::get('/get-all',[LinkController::class,'getAllLinks']);
    });
}));
