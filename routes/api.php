<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => "api"], (function () {

    Route::get("/test", function () {
        return response()->json(["status" => true, "data" => "API Started"]);
    });

    Route::prefix('/common', function () {
    });

    Route::prefix('/user', function () {
    });
}));
