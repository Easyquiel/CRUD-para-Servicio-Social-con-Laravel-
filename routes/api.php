<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\SocialServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/social-service', [SocialServiceController::class, 'store']);
    Route::get('/social-service', [SocialServiceController::class, 'index'])->middleware('admin');
    Route::get('/social-service/{id}', [SocialServiceController::class, 'show']);
    Route::put('/social-service/{id}', [SocialServiceController::class, 'update'])->middleware('admin');
    Route::delete('/social-service/{id}', [SocialServiceController::class, 'destroy'])->middleware('admin');
});
