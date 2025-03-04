<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy']);


Route::get('/login', function () {
    return redirect('/#/login');
})->name('login');

Route::view('/{any?}', 'dashboard')
    ->where('any', '.*');
