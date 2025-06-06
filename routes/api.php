<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResidenceController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Residence routes
    Route::get('/residences', [ResidenceController::class, 'index']);
    Route::get('/residences/{residence}', [ResidenceController::class, 'show']);
    Route::post('/residences', [ResidenceController::class, 'store'])->middleware('role:provider');
    Route::put('/residences/{residence}', [ResidenceController::class, 'update'])->middleware('role:provider');
    Route::delete('/residences/{residence}', [ResidenceController::class, 'destroy'])->middleware('role:provider');

    // Activity routes
    Route::get('/activities', [ActivityController::class, 'index']);
    Route::get('/activities/{activity}', [ActivityController::class, 'show']);
    Route::post('/activities', [ActivityController::class, 'store'])->middleware('role:provider');
    Route::put('/activities/{activity}', [ActivityController::class, 'update'])->middleware('role:provider');
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->middleware('role:provider');

    // Booking routes
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->middleware('role:provider');
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])->middleware('role:provider');
    Route::get('/bookings/{booking}/files/{fileType}', [BookingController::class, 'downloadFile']);

    // Review routes
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // Bookmark routes
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy']);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

    // Discount routes
    Route::post('/discounts/check', [DiscountController::class, 'check']);

    // Payment routes
    Route::get('/payments/bank-accounts', [TransactionController::class, 'getBankAccounts']);
    Route::get('/payments/{transaction}/status', [TransactionController::class, 'checkPaymentStatus']);
}); 