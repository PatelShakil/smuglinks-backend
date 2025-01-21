<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ThemeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WebButtonController;
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
        Route::post('/web-link',[UserController::class,'getWebLink']);
        Route::post('/link-click', [LinkController::class, 'registerLinkClick']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/verify-forgot-password', [AuthController::class, 'verifyForgotPassword']);
    });

    Route::prefix('/user')->group(function () {
        Route::post('/update-yourself', [UserController::class, 'update']);
        Route::get('/details', [UserController::class, 'getUserDetails']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
        Route::post('/subscribe-to-plan', [SubscriptionController::class, 'subscribePlan']);
        Route::post('/add-profile-image', [UserController::class, 'addProfileImage']);
        Route::get('/remove-profile-image', [UserController::class, 'deleteProfileImage']);
        Route::post('/add-title-bio', [UserController::class, 'addTitleBio']);
        Route::get('/theme/get-all', [ThemeController::class, 'getAllTheme']);
        Route::post('/theme/select-theme', [ThemeController::class, 'selectTheme']);
        Route::post('/bg/set-bg', [ThemeController::class, 'setBg']);
        Route::get('/bg/get-bg', [ThemeController::class, 'getBg']);
        Route::post('/btn/set-btn', [WebButtonController::class, 'setButton']);
        Route::get('/fonts/get-all', [WebButtonController::class, 'getFonts']);
        Route::post('/fonts/set-font', [WebButtonController::class, 'selectFont']);
        Route::post('/update-tab-name',[UserController::class, 'updateTabName']);
    });

    Route::prefix('/admin')->group(function () {
        Route::prefix('/common')->group(function () {
        Route::post('/login',[AdminController::class,'doLogin']);

        });

        Route::post('/add-admin',[AdminController::class,'addAdmin']);


        Route::prefix('/subscription')->group(function () {
            Route::post('/create-plan', [SubscriptionController::class, 'addPlan']);
            Route::get('/get-all', [SubscriptionController::class, 'getAllSubscriptions']);
            Route::post('/add-price',[SubscriptionController::class, 'addPrice']);
            Route::delete('/delete-price/{id}',[SubscriptionController::class, 'deletePrice']);
            Route::post('/update-price',[SubscriptionController::class, 'updatePrice']);
            Route::post('/update-plan',[SubscriptionController::class, 'updatePlan']);

            Route::get('/get-users/{id}',[SubscriptionController::class, 'getUsersByPlan']);
            Route::delete('/delete-subscription/{id}',[SubscriptionController::class, 'deleteSubscription']);
        });

        Route::prefix('/theme')->group(function () {
            Route::post('/add-theme', [ThemeController::class, 'addTheme']);
            Route::get('/get-all', [ThemeController::class, 'getAllTheme']);
            Route::delete('/delete-theme/{id}', [ThemeController::class, 'deleteTheme']);
            Route::post('/update-theme', [ThemeController::class, 'updateTheme']);
        });

        Route::prefix('/font')->group(function () {
            Route::get('/get-all', [WebButtonController::class, 'getFonts']);
            Route::delete('/delete-font/{id}', [WebButtonController::class, 'deleteFont']);
            Route::post('/update-font', [WebButtonController::class, 'updateFont']);
        });

    });

    Route::prefix('link')->group(function () {
        Route::post('/add', [LinkController::class, 'addLink']);
        Route::get('/get-all', [LinkController::class, 'getAllLinks']);
        Route::get('/analytics', [LinkController::class, 'getAnalytics']);
        Route::get('/find/{id}',[LinkController::class, 'getLinkById']);
        Route::post('/delete',[LinkController::class, 'deleteLinkById']);
        Route::post('/edit',[LinkController::class, 'editLink']);
        Route::post('/upload-pdf',[LinkController::class, 'uploadPdf']);
    });

    Route::prefix('product')->group(function () {
        Route::post('/add', [ProductController::class, 'addProduct']);
        Route::get('/get-all', [ProductController::class, 'getProducts']);
        Route::post('/delete', [ProductController::class, 'deleteProduct']);
        Route::post('/delete-image', [ProductController::class, 'deleteProductImage']);
        Route::post('/add-image', [ProductController::class, 'addProductImage']);
        Route::post('/edit', [ProductController::class, 'editProduct']);
        Route::get('/find/{id}', [ProductController::class, 'getProduct']);
    });

    Route::prefix('subscription')->group(function () {
        Route::get('/get-all', [SubscriptionController::class, 'getAllSubscriptions']);
Route::get('/get-current', [SubscriptionController::class, 'getCurrentSubscriptions']);
    });
 
   
}));
