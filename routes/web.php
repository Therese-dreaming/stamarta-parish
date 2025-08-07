<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PriestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;

// Frontend Routes - Keep your existing routes and add CMS pages
Route::get('/', function () {
    // Always use your existing home view - CMS pages won't override this
    return view('home');
})->name('home');

// CMS Pages - Dynamic pages from the CMS
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Keep your existing routes - you'll need to add these back
Route::get('/contact', function () {
    // Always use your existing contact view - CMS pages won't override this
    return view('contact');
})->name('contact');

// Add all the routes your existing files reference
# Services routes (public)
Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');

Route::get('/userServices', function () {
    return redirect()->route('services.index');
})->name('userServices');

Route::get('/devotion', function () {
    return view('devotion');
})->name('devotion');

Route::get('/ministries', function () {
    return view('ministries');
})->name('ministries');

Route::get('/simbahan', function () {
    return view('simbahan');
})->name('simbahan');

Route::get('/diyosesis', function () {
    return view('diyosesis');
})->name('diyosesis');

Route::get('/kaparian', function () {
    return view('kaparian');
})->name('kaparian');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');



# Booking routes (require auth and verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/services/{service}/book', [App\Http\Controllers\BookingController::class, 'book'])->name('services.book');
    Route::get('/booking/step1/{service}', [App\Http\Controllers\BookingController::class, 'book'])->name('booking.step1'); // Added this route
    Route::post('/booking/step1/{service}', [App\Http\Controllers\BookingController::class, 'step1'])->name('booking.step1.store');
    Route::get('/booking/step2/{service}', [App\Http\Controllers\BookingController::class, 'step2'])->name('booking.step2');
    Route::post('/booking/step2/{service}', [App\Http\Controllers\BookingController::class, 'step2Store'])->name('booking.step2.store');
    Route::get('/booking/step3/{service}', [App\Http\Controllers\BookingController::class, 'step3'])->name('booking.step3');
    Route::post('/booking/step3/{service}', [App\Http\Controllers\BookingController::class, 'step3Store'])->name('booking.step3.store');
    Route::get('/booking/confirmation/{booking}', [App\Http\Controllers\BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/my-bookings', [App\Http\Controllers\BookingController::class, 'myBookings'])->name('services.my-bookings');
    
    // AJAX route for time slots
    Route::get('/booking/time-slots/{service}', [App\Http\Controllers\BookingController::class, 'getTimeSlots'])->name('booking.time-slots');
    
    // Payment and cancel routes
    Route::post('/booking/submit-payment/{booking}', [App\Http\Controllers\BookingController::class, 'submitPayment'])->name('booking.submit-payment');
    Route::get('/booking/cancel/{booking}', [App\Http\Controllers\BookingController::class, 'cancelBooking'])->name('booking.cancel');
});

// Add register route for welcome page
Route::get('/register', function () {
    return redirect()->route('signup');
})->name('register');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login.post');
    
    Route::get('/signup', [App\Http\Controllers\Auth\AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [App\Http\Controllers\Auth\AuthController::class, 'signup'])->name('signup.post');
    
    Route::get('/forgot-password', [App\Http\Controllers\Auth\AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\AuthController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password/{token}', [App\Http\Controllers\Auth\AuthController::class, 'resetPassword'])->name('password.update');
});

// Email Verification Routes
Route::get('/verify-email/{token}', [App\Http\Controllers\Auth\AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::get('/verify-email', [App\Http\Controllers\Auth\AuthController::class, 'showVerificationNotice'])->name('verification.notice');
Route::post('/verify-email', [App\Http\Controllers\Auth\AuthController::class, 'resendVerification'])->name('verification.send');

// Logout Route
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // CMS Routes
    Route::prefix('cms')->name('cms.')->group(function () {
        // Pages
        Route::resource('pages', AdminPageController::class);
        Route::post('pages/{page}/toggle-publish', [AdminPageController::class, 'togglePublish'])->name('pages.toggle-publish');
        Route::get('pages/{page}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
        
        // Media
        Route::resource('media', MediaController::class);
        Route::get('media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
        Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    });

    // Priest Management
    Route::resource('priests', PriestController::class);
    Route::post('priests/{priest}/toggle-status', [PriestController::class, 'toggleStatus'])->name('priests.toggle-status');

    // User Management
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Service Management
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    
    // Booking Management
    Route::get('bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{booking}/acknowledge', [App\Http\Controllers\Admin\BookingController::class, 'acknowledge'])->name('bookings.acknowledge');
    Route::post('bookings/{booking}/verify-payment', [App\Http\Controllers\Admin\BookingController::class, 'verifyPayment'])->name('bookings.verify-payment');
    Route::post('bookings/{booking}/complete', [App\Http\Controllers\Admin\BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('bookings/{booking}/reject', [App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('bookings.reject');
    Route::get('bookings/{booking}/download-document/{documentType}', [App\Http\Controllers\Admin\BookingController::class, 'downloadDocument'])->name('bookings.download-document');
    Route::get('bookings/{booking}/download-payment-proof', [App\Http\Controllers\Admin\BookingController::class, 'downloadPaymentProof'])->name('bookings.download-payment-proof');
    Route::post('bookings/{booking}/update-notes', [App\Http\Controllers\Admin\BookingController::class, 'updateNotes'])->name('bookings.update-notes');
});

// Fallback route for admin pages
Route::fallback(function () {
    return view('welcome');
});
