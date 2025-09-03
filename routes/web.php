<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProviderDiscountController;
use App\Http\Controllers\Provider\VoucherController;
use App\Http\Controllers\ReviewController;


// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Landing Page Route
Route::get('/', function () {
    return view('landing.index');
});

// Password Reset Routes
Route::get('/password/forgot', function () {
    // tampilkan halaman lupa password (buat view jika perlu)
    return view('auth.forgot-password');
})->name('password.request');

// Home Page Route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Public Routes
Route::get('/residences', [ResidenceController::class, 'index'])->name('residences.index');
Route::get('/residences/{slug}', [ResidenceController::class, 'show'])->name('residences.show');
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{slug}', [ActivityController::class, 'show'])->name('activities.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Review Routes
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        // Create booking
        Route::get('/create/{id}', [BookingController::class, 'create'])->name('create');
        Route::get('/create/activity/{id}', [BookingController::class, 'create'])->name('create.activity');
        Route::post('/store', [BookingController::class, 'store'])->name('store');
        
        // View booking details
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        
        // Booking actions
        Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('/{booking}/reschedule', [BookingController::class, 'reschedule'])->name('reschedule');
        Route::post('/{booking}/confirm-payment', [BookingController::class, 'confirmPayment'])->name('confirm-payment');
        
        // Download booking files
        Route::get('/{booking}/download/{fileType}', [BookingController::class, 'downloadFile'])->name('download-file');
        
        // Check discount code
        Route::post('/check-discount', [BookingController::class, 'checkDiscount'])->name('check-discount');
        
        // Booking history
        Route::get('/history', [BookingController::class, 'history'])->name('history');
    });
});

// Provider Routes
Route::middleware(['auth'])->prefix('provider')->name('provider.')->group(function () {
    // Cek role provider di controller, bukan di route
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings/{booking}', [ProviderDashboardController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings/{booking}/approve', [ProviderDashboardController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [ProviderDashboardController::class, 'rejectBooking'])->name('bookings.reject');
    
    // Residence Management
    Route::get('/residences', [ResidenceController::class, 'providerIndex'])->name('residences.index');
    Route::get('/residences/create', [ResidenceController::class, 'create'])->name('residences.create');
    Route::post('/residences', [ResidenceController::class, 'store'])->name('residences.store');
    Route::get('/residences/{id}/edit', [ResidenceController::class, 'edit'])->name('residences.edit');
    Route::put('/residences/{id}', [ResidenceController::class, 'update'])->name('residences.update');
    Route::delete('/residences/{id}', [ResidenceController::class, 'destroy'])->name('residences.destroy');
    
    // Activity Management
    Route::get('/activities', [ActivityController::class, 'providerIndex'])->name('activities.index');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    
    // Discount Management
    Route::get('/discounts', [ProviderDiscountController::class, 'index'])->name('discounts.index');
    Route::get('/discounts/create', [ProviderDiscountController::class, 'create'])->name('discounts.create');
    Route::post('/discounts', [ProviderDiscountController::class, 'store'])->name('discounts.store');
    Route::get('/discounts/{discount}/edit', [ProviderDiscountController::class, 'edit'])->name('discounts.edit');
    Route::put('/discounts/{discount}', [ProviderDiscountController::class, 'update'])->name('discounts.update');
    Route::delete('/discounts/{discount}', [ProviderDiscountController::class, 'destroy'])->name('discounts.destroy');
    Route::get('/discounts/get-items', [ProviderDiscountController::class, 'getItems'])->name('discounts.get-items');

    // Voucher Management
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
    Route::patch('/vouchers/{voucher}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle-status');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    Route::get('/residences', [DashboardController::class, 'residences'])->name('residences');
    Route::get('/activities', [DashboardController::class, 'activities'])->name('activities');
});

// User voucher routes
Route::middleware(['auth'])->group(function () {
    Route::post('/vouchers/validate', [App\Http\Controllers\User\VoucherController::class, 'validateVoucher'])
         ->name('user.vouchers.validate');
    Route::get('/vouchers/available', [App\Http\Controllers\User\VoucherController::class, 'getAvailableVouchers'])
         ->name('user.vouchers.available');
});
