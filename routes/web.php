<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PriestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MinistryFundController;
use App\Http\Controllers\Admin\MinistryBudgetRequestController;
use App\Http\Controllers\Admin\MinistryMemberController;
use App\Http\Controllers\Admin\MinistryController as AdminMinistryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ServiceRatingController;

// Frontend Routes - Keep your existing routes and add CMS pages
Route::get('/', function () {
	// Always use your existing home view - CMS pages won't override this
	return view('home');
})->name('home');

// CMS Pages - Dynamic pages from the CMS
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/page/{page:slug}', [PageController::class, 'show'])->name('page.show');

// Keep your existing routes - you'll need to add these back
Route::get('/contact', function () {
	// Always use your existing contact view - CMS pages won't override this
	return view('contact');
})->name('contact');

// Support Tickets - Redirected to Contact page
Route::get('/support/tickets/create', function() {
    return redirect()->route('contact')->with('info', 'Support tickets are now handled through our Contact page.');
})->name('tickets.create');
Route::post('/support/tickets', [TicketController::class, 'store'])->name('tickets.store');

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

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

// FAQ Chatbot Routes
Route::post('/faq/chat', [FaqController::class, 'chat'])->name('faq.chat');
Route::get('/faq/suggestions', [FaqController::class, 'getSuggestions'])->name('faq.suggestions');
Route::get('/faq/categories', [FaqController::class, 'getCategories'])->name('faq.categories');
Route::get('/faq/category', [FaqController::class, 'getByCategory'])->name('faq.by-category');
Route::get('/faq/all', [FaqController::class, 'getAll'])->name('faq.all');

// Admin FAQ Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
	Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);
	Route::post('faqs/{faq}/toggle-status', [\App\Http\Controllers\Admin\FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
	Route::post('faqs/reorder', [\App\Http\Controllers\Admin\FaqController::class, 'reorder'])->name('faqs.reorder');
});



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
	Route::get('/my-bookings', [App\Http\Controllers\BookingController::class, 'myBookings'])->name('booking.my-bookings');
	Route::get('/my-bookings/{booking}', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
	
	// AJAX route for time slots
	Route::get('/booking/time-slots/{service}', [App\Http\Controllers\BookingController::class, 'getTimeSlots'])->name('booking.time-slots');
	
	// Payment and cancel routes
	Route::get('/booking/payment/{booking}', [App\Http\Controllers\BookingController::class, 'showPayment'])->name('booking.payment');
	Route::post('/booking/submit-payment/{booking}', [App\Http\Controllers\BookingController::class, 'submitPayment'])->name('booking.submit-payment');
	Route::get('/booking/cancel/{booking}', [App\Http\Controllers\BookingController::class, 'cancelBooking'])->name('booking.cancel');
	
	// User Notification Routes
	Route::get('/notifications', [App\Http\Controllers\UserNotificationController::class, 'index'])->name('user.notifications.index');
	Route::post('/notifications/mark-as-read', [App\Http\Controllers\UserNotificationController::class, 'markAsRead'])->name('user.notifications.mark-as-read');
	Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\UserNotificationController::class, 'markAllAsRead'])->name('user.notifications.mark-all-as-read');
	Route::get('/notifications/unread-count', [App\Http\Controllers\UserNotificationController::class, 'getUnreadCount'])->name('user.notifications.unread-count');
	Route::post('/notifications/delete', [App\Http\Controllers\UserNotificationController::class, 'delete'])->name('user.notifications.delete');
	
	// Service Rating Routes
	Route::post('/service-rating', [App\Http\Controllers\ServiceRatingController::class, 'store'])->name('service.rating.store');
	Route::put('/service-rating/{rating}', [App\Http\Controllers\ServiceRatingController::class, 'update'])->name('service.rating.update');
	Route::get('/service-rating', [App\Http\Controllers\ServiceRatingController::class, 'getRating'])->name('service.rating.get');
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
	Route::get('admin-action-counts', [DashboardController::class, 'getAdminActionCounts'])->name('admin-action-counts');
	
	// CMS Routes
	Route::prefix('cms')->name('cms.')->group(function () {
		// Pages
		Route::resource('pages', AdminPageController::class)->except(['destroy']);
		Route::delete('pages/{pageId}', [AdminPageController::class, 'destroy'])->name('pages.destroy');
		Route::post('pages/{pageId}/toggle-publish', [AdminPageController::class, 'togglePublish'])->name('pages.toggle-publish');
		Route::get('pages/{pageId}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
		
		// Media
		Route::resource('media', MediaController::class)->except(['destroy']);
		Route::delete('media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');
		Route::get('media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
		Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
	});
	
	// Priest Management
	Route::resource('priests', PriestController::class);
	Route::post('priests/{priest}/toggle-status', [PriestController::class, 'toggleStatus'])->name('priests.toggle-status');
	Route::get('priests/{priest}/bookings', [PriestController::class, 'bookings'])->name('priests.bookings');
	
	// Priest Leaves Management (Admin)
	Route::post('leaves/{leave}/approve', [\App\Http\Controllers\Admin\PriestLeaveController::class, 'approve'])->name('leaves.approve');
	Route::post('leaves/{leave}/reject', [\App\Http\Controllers\Admin\PriestLeaveController::class, 'reject'])->name('leaves.reject');
	Route::post('leaves/{leave}/complete', [\App\Http\Controllers\Admin\PriestLeaveController::class, 'complete'])->name('leaves.complete');
	
	// User Management
	Route::resource('users', UserController::class);
	Route::get('users-search', [UserController::class, 'search'])->name('users.search');
	Route::post('users/{user}/promote-ministry-head', [UserController::class, 'promoteToMinistryHead'])->name('users.promote-ministry-head');
	Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
	
	// Service Management
	Route::get('services', [ServiceController::class, 'index'])->name('services.index');
	Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');
	Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
	Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
	Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
	
	// Booking Management
	Route::get('bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
	Route::get('bookings/calendar', [App\Http\Controllers\Admin\BookingController::class, 'calendar'])->name('bookings.calendar');
	Route::get('bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
	Route::get('bookings/{booking}/print', [App\Http\Controllers\Admin\BookingController::class, 'print'])->name('bookings.print');
	Route::get('bookings/{booking}/certificate', [App\Http\Controllers\Admin\BookingController::class, 'certificate'])->name('bookings.certificate');
	Route::post('bookings/{booking}/acknowledge', [App\Http\Controllers\Admin\BookingController::class, 'acknowledge'])->name('bookings.acknowledge');
	Route::post('bookings/{booking}/verify-payment', [App\Http\Controllers\Admin\BookingController::class, 'verifyPayment'])->name('bookings.verify-payment');
	Route::post('bookings/{booking}/complete', [App\Http\Controllers\Admin\BookingController::class, 'complete'])->name('bookings.complete');
	Route::post('bookings/{booking}/reject', [App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('bookings.reject');
	Route::get('bookings/{booking}/download-document/{documentType}', [App\Http\Controllers\Admin\BookingController::class, 'downloadDocument'])->name('bookings.download-document');
	Route::get('bookings/{booking}/download-payment-proof', [App\Http\Controllers\Admin\BookingController::class, 'downloadPaymentProof'])->name('bookings.download-payment-proof');
	Route::post('bookings/{booking}/certificate', [App\Http\Controllers\Admin\BookingController::class, 'uploadCertificate'])->name('bookings.certificate.upload');
	Route::delete('bookings/{booking}/certificate', [App\Http\Controllers\Admin\BookingController::class, 'deleteCertificate'])->name('bookings.certificate.delete');
	
	// Parochial Activities Management
	Route::resource('parochial-activities', App\Http\Controllers\Admin\ParochialActivityController::class);
	Route::get('parochial-activities-calendar', [App\Http\Controllers\Admin\ParochialActivityController::class, 'calendar'])->name('parochial-activities.calendar');
	Route::get('blocking-activities', [App\Http\Controllers\Admin\ParochialActivityController::class, 'getBlockingActivities'])->name('parochial-activities.blocking');
	
	// Admin Notification Routes
	Route::get('notifications', [App\Http\Controllers\AdminNotificationController::class, 'index'])->name('admin.notifications.index');
	Route::post('notifications/mark-as-read', [App\Http\Controllers\AdminNotificationController::class, 'markAsRead'])->name('admin.notifications.mark-as-read');
	Route::post('notifications/mark-all-as-read', [App\Http\Controllers\AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-as-read');
	Route::get('notifications/unread-count', [App\Http\Controllers\AdminNotificationController::class, 'getUnreadCount'])->name('admin.notifications.unread-count');
	Route::post('notifications/delete', [App\Http\Controllers\AdminNotificationController::class, 'delete'])->name('admin.notifications.delete');
	
	// Ministries - Fund Overview (admin)
	Route::get('ministries/{ministry}/fund', [MinistryFundController::class, 'index'])->name('ministries.fund');

	// Budget Management
	Route::get('budget-management', [App\Http\Controllers\Admin\BudgetManagementController::class, 'index'])->name('budget-management.index');
	Route::get('budget-management/{transaction}', [App\Http\Controllers\Admin\BudgetManagementController::class, 'show'])->name('budget-management.show');

	// Manual Cash Inflows
	Route::resource('manual-cash-inflows', App\Http\Controllers\Admin\ManualCashInflowController::class);
	Route::post('manual-cash-inflows/{manual_cash_inflow}/approve', [App\Http\Controllers\Admin\ManualCashInflowController::class, 'approve'])->name('manual-cash-inflows.approve');
	Route::post('manual-cash-inflows/{manual_cash_inflow}/reject', [App\Http\Controllers\Admin\ManualCashInflowController::class, 'reject'])->name('manual-cash-inflows.reject');

	// Ministries - CRUD (admin)
	Route::get('ministries', [AdminMinistryController::class, 'index'])->name('ministries.index');
	Route::get('ministries/create', [AdminMinistryController::class, 'create'])->name('ministries.create');
	Route::post('ministries', [AdminMinistryController::class, 'store'])->name('ministries.store');
	Route::get('ministries/{ministry}/edit', [AdminMinistryController::class, 'edit'])->name('ministries.edit');
	Route::put('ministries/{ministry}', [AdminMinistryController::class, 'update'])->name('ministries.update');
	Route::delete('ministries/{ministry}', [AdminMinistryController::class, 'destroy'])->name('ministries.destroy');

	// Ministries - Ministry Activities
	Route::get('ministries/ministry-activities', [MinistryBudgetRequestController::class, 'index'])->name('ministries.ministry-activities.index');
	Route::get('ministries/ministry-activities/{requestModel}', [MinistryBudgetRequestController::class, 'show'])->name('ministries.ministry-activities.show');
	Route::post('ministries/{ministry}/ministry-activities', [MinistryBudgetRequestController::class, 'store'])->name('ministries.ministry-activities.store');
	Route::post('ministries/ministry-activities/{requestModel}/approve', [MinistryBudgetRequestController::class, 'approve'])->name('ministries.ministry-activities.approve');
	Route::post('ministries/ministry-activities/{requestModel}/reject', [MinistryBudgetRequestController::class, 'reject'])->name('ministries.ministry-activities.reject');

	// Ministries - Members CRUD
	Route::get('ministries/{ministry}/members', [MinistryMemberController::class, 'index'])->name('ministries.members.index');
	Route::get('ministries/{ministry}/members/create', [MinistryMemberController::class, 'create'])->name('ministries.members.create');
	Route::post('ministries/{ministry}/members', [MinistryMemberController::class, 'store'])->name('ministries.members.store');
	Route::get('ministries/{ministry}/members/{member}/edit', [MinistryMemberController::class, 'edit'])->name('ministries.members.edit');
	Route::put('ministries/{ministry}/members/{member}', [MinistryMemberController::class, 'update'])->name('ministries.members.update');
	Route::delete('ministries/{ministry}/members/{member}', [MinistryMemberController::class, 'destroy'])->name('ministries.members.destroy');
});

// Staff Routes
Route::prefix('staff')->name('staff.')->middleware(['auth', 'staff'])->group(function () {
	// Dashboard (only staff-specific controller)
	Route::get('/', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
	Route::get('staff-action-counts', [App\Http\Controllers\Staff\DashboardController::class, 'getStaffActionCounts'])->name('staff-action-counts');
	
	// Booking Management (using admin controller)
	Route::get('bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
	Route::get('bookings/calendar', [App\Http\Controllers\Admin\BookingController::class, 'calendar'])->name('bookings.calendar');
	Route::get('bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
	Route::get('bookings/{booking}/print', [App\Http\Controllers\Admin\BookingController::class, 'print'])->name('bookings.print');
	Route::get('bookings/{booking}/certificate', [App\Http\Controllers\Admin\BookingController::class, 'certificate'])->name('bookings.certificate');
	Route::post('bookings/{booking}/acknowledge', [App\Http\Controllers\Admin\BookingController::class, 'acknowledge'])->name('bookings.acknowledge');
	Route::post('bookings/{booking}/verify-payment', [App\Http\Controllers\Admin\BookingController::class, 'verifyPayment'])->name('bookings.verify-payment');
	Route::post('bookings/{booking}/complete', [App\Http\Controllers\Admin\BookingController::class, 'complete'])->name('bookings.complete');
	Route::post('bookings/{booking}/reject', [App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('bookings.reject');
	Route::get('bookings/{booking}/download-document/{documentType}', [App\Http\Controllers\Admin\BookingController::class, 'downloadDocument'])->name('bookings.download-document');
	Route::get('bookings/{booking}/download-payment-proof', [App\Http\Controllers\Admin\BookingController::class, 'downloadPaymentProof'])->name('bookings.download-payment-proof');
	Route::post('bookings/{booking}/certificate', [App\Http\Controllers\Admin\BookingController::class, 'uploadCertificate'])->name('bookings.certificate.upload');
	Route::delete('bookings/{booking}/certificate', [App\Http\Controllers\Admin\BookingController::class, 'deleteCertificate'])->name('bookings.certificate.delete');
	
	// Parochial Activities Management (using admin controller)
	Route::resource('parochial-activities', App\Http\Controllers\Admin\ParochialActivityController::class);
	Route::get('parochial-activities-calendar', [App\Http\Controllers\Admin\ParochialActivityController::class, 'calendar'])->name('parochial-activities.calendar');
	Route::get('blocking-activities', [App\Http\Controllers\Admin\ParochialActivityController::class, 'getBlockingActivities'])->name('parochial-activities.blocking');
	
	// CMS Routes (using admin controllers)
	Route::prefix('cms')->name('cms.')->group(function () {
		// Pages
		Route::resource('pages', AdminPageController::class)->except(['destroy']);
		Route::delete('pages/{pageId}', [AdminPageController::class, 'destroy'])->name('pages.destroy');
		Route::post('pages/{pageId}/toggle-publish', [AdminPageController::class, 'togglePublish'])->name('pages.toggle-publish');
		Route::get('pages/{pageId}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
		
		// Media
		Route::resource('media', MediaController::class)->except(['destroy']);
		Route::delete('media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');
		Route::get('media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
		Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
	});
	
	// Direct CMS routes for easier access
	Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index');
	Route::get('pages/create', [AdminPageController::class, 'create'])->name('pages.create');
	Route::post('pages', [AdminPageController::class, 'store'])->name('pages.store');
	Route::get('pages/{page}', [AdminPageController::class, 'show'])->name('pages.show');
	Route::get('pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
	Route::put('pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
	Route::delete('pages/{pageId}', [AdminPageController::class, 'destroy'])->name('pages.destroy');
	Route::post('pages/{page}/toggle-publish', [AdminPageController::class, 'togglePublish'])->name('pages.toggle-publish');
	Route::get('pages/{page}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
	
	// View Only Routes (using admin controllers)
	Route::get('priests', [PriestController::class, 'index'])->name('priests.index');
	Route::get('priests/{priest}', [PriestController::class, 'show'])->name('priests.show');
	Route::get('priests/{priest}/bookings', [PriestController::class, 'bookings'])->name('priests.bookings');
	
	Route::get('services', [ServiceController::class, 'index'])->name('services.index');
	Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');
	
	// User Management (view-only for staff)
	Route::get('users', [UserController::class, 'index'])->name('users.index');
	Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
	
	// Staff Notification Routes
	Route::get('notifications', [App\Http\Controllers\StaffNotificationController::class, 'index'])->name('notifications.index');
	Route::post('notifications/mark-as-read', [App\Http\Controllers\StaffNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
	Route::post('notifications/mark-all-as-read', [App\Http\Controllers\StaffNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
	Route::get('notifications/unread-count', [App\Http\Controllers\StaffNotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
	Route::post('notifications/delete', [App\Http\Controllers\StaffNotificationController::class, 'delete'])->name('notifications.delete');
});

// Priest Routes
Route::prefix('priest')->name('priest.')->middleware(['auth', 'priest'])->group(function () {
	// Dashboard
	Route::get('/', [App\Http\Controllers\Priest\DashboardController::class, 'index'])->name('dashboard');
	
	// Booking Management (view-only for assigned bookings)
	Route::get('bookings', [App\Http\Controllers\Priest\BookingController::class, 'index'])->name('bookings.index');
	Route::get('bookings/calendar', [App\Http\Controllers\Priest\BookingController::class, 'calendar'])->name('bookings.calendar');
	Route::get('bookings/{booking}', [App\Http\Controllers\Priest\BookingController::class, 'show'])->name('bookings.show');
	Route::get('bookings/{booking}/download-document/{documentType}', [App\Http\Controllers\Priest\BookingController::class, 'downloadDocument'])->name('bookings.download-document');
	Route::get('bookings/{booking}/download-payment-proof', [App\Http\Controllers\Priest\BookingController::class, 'downloadPaymentProof'])->name('bookings.download-payment-proof');
		
	// Priest Profile and Leave Management
	Route::get('profile', [App\Http\Controllers\Priest\ProfileController::class, 'edit'])->name('profile.edit');
	Route::put('profile', [App\Http\Controllers\Priest\ProfileController::class, 'update'])->name('profile.update');
	Route::get('leave', [App\Http\Controllers\Priest\LeaveController::class, 'create'])->name('leave.create');
	Route::post('leave', [App\Http\Controllers\Priest\LeaveController::class, 'store'])->name('leave.store');
	Route::get('leave/existing', [App\Http\Controllers\Priest\LeaveController::class, 'getExistingLeaves'])->name('leave.existing');
	
	// Priest Notification Routes
	Route::get('notifications', [App\Http\Controllers\PriestNotificationController::class, 'index'])->name('notifications.index');
	Route::post('notifications/mark-as-read', [App\Http\Controllers\PriestNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
	Route::post('notifications/mark-all-as-read', [App\Http\Controllers\PriestNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
	Route::get('notifications/unread-count', [App\Http\Controllers\PriestNotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
	Route::post('notifications/delete', [App\Http\Controllers\PriestNotificationController::class, 'delete'])->name('notifications.delete');
});

// Ministry Head Routes
Route::prefix('ministry')->name('ministry.')->middleware(['auth', 'ministry_head'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Ministry\DashboardController::class, 'index'])->name('dashboard');
    Route::get('members', [\App\Http\Controllers\Ministry\MemberController::class, 'index'])->name('members.index');
    Route::get('members/create', [\App\Http\Controllers\Ministry\MemberController::class, 'create'])->name('members.create');
    Route::post('members', [\App\Http\Controllers\Ministry\MemberController::class, 'store'])->name('members.store');
    Route::get('members/search-users', [\App\Http\Controllers\Ministry\MemberController::class, 'searchUsers'])->name('members.search-users');
    Route::get('members/{member}/edit', [\App\Http\Controllers\Ministry\MemberController::class, 'edit'])->name('members.edit');
    Route::put('members/{member}', [\App\Http\Controllers\Ministry\MemberController::class, 'update'])->name('members.update');
    Route::delete('members/{member}', [\App\Http\Controllers\Ministry\MemberController::class, 'destroy'])->name('members.destroy');

    // Activities (with integrated budget requests)
    Route::get('activities', [\App\Http\Controllers\Ministry\ActivityController::class, 'index'])->name('activities.index');
    Route::get('activities/create', [\App\Http\Controllers\Ministry\ActivityController::class, 'create'])->name('activities.create');
    Route::post('activities', [\App\Http\Controllers\Ministry\ActivityController::class, 'store'])->name('activities.store');
    Route::get('activities/{activity}', [\App\Http\Controllers\Ministry\ActivityController::class, 'show'])->name('activities.show');
    Route::get('activities/{activity}/edit', [\App\Http\Controllers\Ministry\ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('activities/{activity}', [\App\Http\Controllers\Ministry\ActivityController::class, 'update'])->name('activities.update');
    Route::delete('activities/{activity}', [\App\Http\Controllers\Ministry\ActivityController::class, 'destroy'])->name('activities.destroy');

    Route::post('activities/check-conflicts', [\App\Http\Controllers\Ministry\ActivityController::class, 'checkConflicts'])->name('activities.check-conflicts');
    Route::get('activities/test-conflicts', [\App\Http\Controllers\Ministry\ActivityController::class, 'testConflicts'])->name('activities.test-conflicts');

    // Budget Management
    Route::get('budget-management', [\App\Http\Controllers\Ministry\BudgetManagementController::class, 'index'])->name('budget-management.index');
    Route::get('budget-management/show', [\App\Http\Controllers\Ministry\BudgetManagementController::class, 'show'])->name('budget-management.show');

    // Manual Cash Inflows
    Route::resource('manual-cash-inflows', \App\Http\Controllers\Ministry\ManualCashInflowController::class)
        ->parameters(['manual-cash-inflows' => 'manual_cash_inflow']);
});

// Fallback route for admin pages
Route::fallback(function () {
	return view('welcome');
});
