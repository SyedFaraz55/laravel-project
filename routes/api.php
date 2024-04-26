<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserLikeController;
use App\Mail\MailableName;
use Illuminate\Support\Facades\Mail;

Route::group(["prefix" => "v1", "namespace" => "App/Http/Controllers/Api/V1"], function() {
    Route::apiResource('users', UserController::class);
    Route::get("/users",[UserController::class, 'index']);
    Route::get("/user/pagination",[UserController::class, 'list']);
    Route::post("/users",[UserController::class, 'store']);
    // Route::post("/user/{user}/reactions",[UserController::class, 'updateReactions']);

        // User likes routes
    Route::get('/user-likes', [UserLikeController::class, 'list']);
    Route::get('users/{user_id}/likes', [UserLikeController::class, 'index']);
    Route::post('users/likes', [UserLikeController::class, 'store']);
    
});

