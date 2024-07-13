<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => "api"], (function () {

    Route::prefix('/common')->group(function () {
        Route::get('/test', function () {
            return response()->json(["status" => true, "data" => "SmugLinks API Started"]);
        });

        Route::get('/check-user-exists/{username}', [UserController::class, 'checkUserExists']);
    });

    Route::prefix('/user')->group(function () {
        
    });
}));
