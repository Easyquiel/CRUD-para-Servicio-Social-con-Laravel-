<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\SocialServiceController;
use App\Models\SocialServiceStudent;
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

    // Rutas de eventos
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events/{event}', [EventController::class, 'show']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);

    // Rutas específicas para servicio social
    Route::get('/social-service/{id}/events', function ($id) {
        $student = SocialServiceStudent::findOrFail($id);
        $events = $student->events()->with(['creator', 'participants'])->get();
        return response()->json($events);
    });
});