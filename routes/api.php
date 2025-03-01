<?php

use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\User\EventUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::get('/events', [EventUserController::class, 'getEvents']);
Route::get('/events/{id}', [EventUserController::class, 'showEvent']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/events/{id}/register', [EventUserController::class, 'registerForEvent']);
    Route::post('/events/{id}/unregister', [EventUserController::class, 'unregisterForEvent']);
    Route::post('/events/{id}/reviews', [EventUserController::class, 'storeEventReview']);
});

// Admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/events', [EventAdminController::class, 'indexEvents']);
});
