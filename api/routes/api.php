<?php

use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'apiLogin']);
Route::post('/logout', [\App\Http\Controllers\LoginController::class, 'apiLogout']);
Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'apiRegister']);
Route::post('/register-driver', [\App\Http\Controllers\RegisterController::class, 'apiRegisterDriver']);
Route::post('/register-driver-docs', [\App\Http\Controllers\RegisterController::class, 'apiRegisterDriverDocs']);

// Home route
Route::get('/', [\App\Http\Controllers\HomeController::class, 'apiIndex']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'apiShow']);
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'apiUpdate']);
    
    // Password change
    Route::get('/password/change', [\App\Http\Controllers\PasswordChangeController::class, 'apiShow']);
    Route::put('/password/change', [\App\Http\Controllers\PasswordChangeController::class, 'apiUpdate']);
    
    // Driver Profile routes
    Route::get('/driver/profile', [\App\Http\Controllers\DriverProfileController::class, 'apiShow']);
    Route::put('/driver/vehicle-photos', [\App\Http\Controllers\DriverProfileController::class, 'apiUpdateVehiclePhotos']);
    Route::get('/driver/profile/{id}', [\App\Http\Controllers\DriverProfileController::class, 'apiShowPublic']);
    
    // Driver Ride Management routes
    Route::get('/driver/ride-management', [\App\Http\Controllers\DriverRideManagementController::class, 'apiIndex']);
    Route::get('/driver/rides/create', [\App\Http\Controllers\DriverRideManagementController::class, 'apiCreate']);
    Route::post('/driver/rides', [\App\Http\Controllers\DriverRideManagementController::class, 'apiStore']);
    Route::get('/driver/my-rides', [\App\Http\Controllers\DriverRideManagementController::class, 'apiMyRides']);
    Route::get('/driver/rides/{id}/edit', [\App\Http\Controllers\DriverRideManagementController::class, 'apiEdit']);
    Route::put('/driver/rides/{id}', [\App\Http\Controllers\DriverRideManagementController::class, 'apiUpdate']);
    Route::get('/driver/rides/{id}/customers', [\App\Http\Controllers\DriverRideManagementController::class, 'apiShowRideCustomers']);
    Route::get('/driver/earnings', [\App\Http\Controllers\DriverRideManagementController::class, 'apiEarnings']);
    
    // Find Rides
    Route::get('/find-rides', [\App\Http\Controllers\BookingController::class, 'apiFindRides']);
    
    // Booking routes
    Route::get('/booking/payment/{id}/{tripType?}', [\App\Http\Controllers\BookingController::class, 'apiShowPaymentPage']);
    Route::get('/booking/seat-selection/{id}/{tripType?}', [\App\Http\Controllers\BookingController::class, 'apiShowSeatSelection']);
    Route::post('/booking/seat-selection/{id}/{tripType?}', [\App\Http\Controllers\BookingController::class, 'apiProcessSeatSelection']);
    Route::post('/booking/process/{id}/{tripType?}', [\App\Http\Controllers\BookingController::class, 'apiProcessBooking']);
    Route::get('/booking/thank-you/{id}', [\App\Http\Controllers\BookingController::class, 'apiShowThankYou']);
    Route::get('/booking/confirmation/{id}', [\App\Http\Controllers\BookingController::class, 'apiShowConfirmation']);
    
    // Payment routes
    Route::get('/payment/{id}/{tripType?}', [\App\Http\Controllers\PaymentController::class, 'apiShowPaymentPage']);
    Route::post('/payment/process/{id}/{tripType?}', [\App\Http\Controllers\PaymentController::class, 'apiProcessPayment']);
    Route::get('/payment/qr/{id}/{tripType?}', [\App\Http\Controllers\PaymentController::class, 'apiShowQRPayment']);
    
    // User Bookings
    Route::get('/user/bookings', [\App\Http\Controllers\UserBookingController::class, 'apiIndex']);
    Route::get('/user/bookings/{id}', [\App\Http\Controllers\UserBookingController::class, 'apiShow']);
    Route::get('/user/bookings/{id}/receipt', [\App\Http\Controllers\UserBookingController::class, 'apiPrintReceipt']);
    
    // Ride Completion routes
    Route::post('/driver/rides/{id}/ongoing/{tripType?}', [\App\Http\Controllers\RideCompletionController::class, 'apiMarkAsOngoing']);
    Route::post('/driver/rides/{id}/complete/{tripType?}', [\App\Http\Controllers\RideCompletionController::class, 'apiMarkAsCompleted']);
    Route::get('/user/bookings/{id}/review/{tripType?}', [\App\Http\Controllers\RideCompletionController::class, 'apiShowReviewForm']);
    Route::post('/user/bookings/{id}/review/{tripType?}', [\App\Http\Controllers\RideCompletionController::class, 'apiSubmitReview']);
    Route::get('/driver/rides/{id}/reviews', [\App\Http\Controllers\RideCompletionController::class, 'apiViewRideReviews']);
    Route::get('/driver/reviews', [\App\Http\Controllers\RideCompletionController::class, 'apiViewAllReviews']);
    
    // Misc routes
    Route::get('/rides', [\App\Http\Controllers\BookingController::class, 'apiAvailableRides']);
});

// Admin authentication (public)
Route::post('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'apiLogin']);
Route::post('/admin/logout', [\App\Http\Controllers\AdminAuthController::class, 'apiLogout']);

// Admin protected routes
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'apiIndex']);

    // Users
    Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'apiIndex']);
    Route::post('/users', [\App\Http\Controllers\AdminUserController::class, 'apiStore']);
    Route::get('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'apiShow']);
    Route::put('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'apiUpdate']);
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'apiDestroy']);

    // Drivers
    Route::get('/drivers', [\App\Http\Controllers\AdminDriverController::class, 'apiIndex']);
    Route::post('/drivers', [\App\Http\Controllers\AdminDriverController::class, 'apiStore']);
    Route::get('/drivers/{id}', [\App\Http\Controllers\AdminDriverController::class, 'apiShow']);
    Route::put('/drivers/{id}', [\App\Http\Controllers\AdminDriverController::class, 'apiUpdate']);
    Route::delete('/drivers/{id}', [\App\Http\Controllers\AdminDriverController::class, 'apiDestroy']);

    // Rides
    Route::get('/rides', [\App\Http\Controllers\AdminRideController::class, 'apiIndex']);
    Route::post('/rides', [\App\Http\Controllers\AdminRideController::class, 'apiStore']);
    Route::get('/rides/{id}', [\App\Http\Controllers\AdminRideController::class, 'apiShow']);
    Route::put('/rides/{id}', [\App\Http\Controllers\AdminRideController::class, 'apiUpdate']);
    Route::delete('/rides/{id}', [\App\Http\Controllers\AdminRideController::class, 'apiDestroy']);
    Route::get('/rides/{id}/passengers', [\App\Http\Controllers\AdminRideController::class, 'apiPassengers']);

    // Driver Documents
    Route::get('/driver-documents', [\App\Http\Controllers\AdminDriverDocumentController::class, 'apiIndex']);
    Route::post('/driver-documents', [\App\Http\Controllers\AdminDriverDocumentController::class, 'apiStore']);
    Route::get('/driver-documents/{id}', [\App\Http\Controllers\AdminDriverDocumentController::class, 'apiShow']);
    Route::put('/driver-documents/{id}', [\App\Http\Controllers\AdminDriverDocumentController::class, 'apiUpdate']);
    Route::delete('/driver-documents/{id}', [\App\Http\Controllers\AdminDriverDocumentController::class, 'apiDestroy']);
    Route::post('/driver-documents/{id}/verify', [\App\Http\Controllers\AdminDriverDocumentController::class, 'apiVerify']);
    Route::post('/driver-documents/{id}/unverify', [\App\Http\Controllers\AdminDriverDocumentController::class, 'apiUnverify']);
}); 