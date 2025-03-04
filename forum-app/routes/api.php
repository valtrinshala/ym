<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('posts', PostController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

