<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, 
    TranslationController
};

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/translations', [TranslationController::class, 'index']);
    Route::post('/translations', [TranslationController::class, 'store']);
    Route::put('/translations/{id}', [TranslationController::class, 'update']);
    Route::delete('/translations/{id}', [TranslationController::class, 'destroy']);
});