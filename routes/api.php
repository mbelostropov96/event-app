<?php

use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\EventUserController;
use App\Http\Controllers\User\UserProfileController;
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

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public routes
Route::get('/events', [EventUserController::class, 'getEvents']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // User profile
    Route::get('/profile/registrations', [UserProfileController::class, 'getRegistrations']);
    Route::get('/profile/reviews', [UserProfileController::class, 'getReviews']);
    Route::delete('/profile/reviews/{id}', [UserProfileController::class, 'deleteReview']);
    
    // Events
    Route::get('/events/{id}', [EventUserController::class, 'showEvent']);
    Route::post('/events/{id}/register', [EventUserController::class, 'registerForEvent']);
    Route::post('/events/{id}/unregister', [EventUserController::class, 'unregisterForEvent']);
    Route::post('/events/{id}/reviews', [EventUserController::class, 'storeEventReview']);
    Route::get('/events/{id}/reviews', [EventUserController::class, 'getEventReviews']);
});

// Admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Dashboard stats
    Route::get('/stats', [StatsController::class, 'index']);
    
    // Events
    Route::get('/events', [EventAdminController::class, 'indexEvents']);
    Route::get('/events/{id}', [EventAdminController::class, 'showEvent']);
    Route::post('/events', [EventAdminController::class, 'storeEvent']);
    Route::put('/events/{id}', [EventAdminController::class, 'updateEvent']);
    Route::delete('/events/{id}', [EventAdminController::class, 'destroyEvent']);
    
    // Event reviews
    Route::get('/reviews', [ReviewAdminController::class, 'index']);
    Route::get('/reviews/{id}', [ReviewAdminController::class, 'show']);
    Route::post('/reviews/moderate', [ReviewAdminController::class, 'moderate']);
    Route::delete('/reviews/{id}', [ReviewAdminController::class, 'destroy']);
    
    // Users
    Route::get('/users', [UserAdminController::class, 'index']);
    Route::get('/users/{id}', [UserAdminController::class, 'show']);
    Route::get('/users/{id}/stats', [UserAdminController::class, 'getUserStats']);
});
