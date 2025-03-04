<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('posts', PostController::class);

    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
    Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');


    Route::get('/user', function (Request $request) {
        return $request->user();
    });    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/email/verify/send', [VerificationController::class, 'sendVerification']);

});

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

