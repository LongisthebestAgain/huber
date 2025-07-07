<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\DriverProfileController;
use App\Http\Controllers\DriverRideManagementController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RideCompletionController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDriverController;
use App\Http\Controllers\AdminRideController;
use App\Http\Controllers\AdminDriverDocumentController;
use App\Http\Controllers\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showChooseRole'])->name('register');

Route::get('/register/choose-role', function () {
    return view('choose-role');
});

Route::get('/register/user', [AuthController::class, 'showUserRegistration'])->name('register.user');

Route::get('/register/driver', [AuthController::class, 'showDriverRegistration'])->name('register.driver');

Route::get('/register/driver-docs', function () {
    return view('register-driver-docs');
});

// Profile management routes
Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Driver-specific routes
Route::get('/driver/profile', [DriverProfileController::class, 'show'])->name('driver.profile');
Route::put('/driver/vehicle-photos', [DriverProfileController::class, 'updateVehiclePhotos'])->name('driver.vehicle-photos.update');
Route::get('/driver/profile/{driverId}', [\App\Http\Controllers\DriverProfileController::class, 'showPublic'])->name('driver.profile.public');
Route::get('/driver/verification-pending', function () {
    return view('driver-verification-pending');
})->name('driver.verification.pending');

// Ride Management for drivers (only verified drivers)
Route::middleware('driver.verified')->group(function () {
    Route::get('/driver/ride-management', [DriverRideManagementController::class, 'index'])->name('driver.ride.management');
    Route::get('/driver/rides/create', [DriverRideManagementController::class, 'create'])->name('driver.rides.create');
    Route::post('/driver/rides', [DriverRideManagementController::class, 'store'])->name('driver.rides.store');
    Route::get('/driver/my-rides', [DriverRideManagementController::class, 'myRides'])->name('driver.my-rides');
    Route::get('/driver/rides/{ride}/edit', [DriverRideManagementController::class, 'edit'])->name('driver.rides.edit');
    Route::put('/driver/rides/{ride}', [DriverRideManagementController::class, 'update'])->name('driver.rides.update');
    Route::get('/driver/rides/{ride}/customers/{tripType?}', [DriverRideManagementController::class, 'showRideCustomers'])->name('driver.ride.customers');
    Route::get('/driver/earnings', [DriverRideManagementController::class, 'earnings'])->name('driver.earnings');
    
    // Ride completion and review routes
    Route::post('/driver/rides/{rideId}/ongoing/{tripType?}', [RideCompletionController::class, 'markAsOngoing'])->name('driver.ride.ongoing');
    Route::post('/driver/rides/{rideId}/complete/{tripType?}', [RideCompletionController::class, 'markAsCompleted'])->name('driver.ride.complete');
    Route::get('/driver/rides/{rideId}/reviews', [RideCompletionController::class, 'viewRideReviews'])->name('driver.ride.reviews');
    Route::get('/driver/reviews', [RideCompletionController::class, 'viewAllReviews'])->name('driver.reviews');
});

// Find rides route (accessible to all users)
Route::get('/find-rides', [DriverRideManagementController::class, 'findRides'])->name('find.rides');

// Booking routes
Route::get('/booking/payment/{rideId}/{tripType?}', [BookingController::class, 'showPaymentPage'])->name('booking.payment');
Route::get('/booking/seat-selection/{rideId}/{tripType?}', [BookingController::class, 'showSeatSelection'])->name('booking.seat-selection');
Route::post('/booking/seat-selection/{rideId}/{tripType?}', [BookingController::class, 'processSeatSelection'])->name('booking.process-seat-selection');
Route::post('/booking/process/{rideId}/{tripType?}', [BookingController::class, 'processBooking'])->name('booking.process');
Route::get('/booking/thank-you/{bookingId}', [BookingController::class, 'showThankYou'])->name('booking.thank-you');
Route::get('/booking/confirmation/{bookingId}', [BookingController::class, 'showConfirmation'])->name('booking.confirmation');

// Payment routes
Route::get('/payment/{rideId}/{tripType?}', [PaymentController::class, 'showPaymentPage'])->name('payment.show');
Route::post('/payment/process/{rideId}/{tripType?}', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/qr/{rideId}/{tripType?}', [PaymentController::class, 'showQRPayment'])->name('payment.qr');

// User booking routes
Route::get('/user/bookings', [UserBookingController::class, 'index'])->name('user.bookings');
Route::get('/user/bookings/{bookingId}', [UserBookingController::class, 'show'])->name('user.booking.details');
Route::get('/user/bookings/{bookingId}/receipt', [UserBookingController::class, 'printReceipt'])->name('user.booking.receipt');

// User review routes
Route::get('/user/bookings/{bookingId}/review/{tripType?}', [RideCompletionController::class, 'showReviewForm'])->name('user.booking.review');
Route::post('/user/bookings/{bookingId}/review/{tripType?}', [RideCompletionController::class, 'submitReview'])->name('user.booking.review.submit');

// Password change routes
Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
Route::put('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.submit');

// Stubs for navbar links
Route::get('/rides', function () { return 'Available Rides'; })->name('rides');
Route::get('/user/history', function () { return 'User History'; })->name('user.history');

// Admin login routes
Route::get('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin dashboard (protected)
Route::middleware('admin.auth')->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Admin user CRUD
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    
    // Admin driver CRUD
    Route::get('/admin/drivers', [AdminDriverController::class, 'index'])->name('admin.drivers.index');
    Route::get('/admin/drivers/create', [AdminDriverController::class, 'create'])->name('admin.drivers.create');
    Route::post('/admin/drivers', [AdminDriverController::class, 'store'])->name('admin.drivers.store');
    Route::get('/admin/drivers/{id}', [AdminDriverController::class, 'show'])->name('admin.drivers.show');
    Route::get('/admin/drivers/{id}/edit', [AdminDriverController::class, 'edit'])->name('admin.drivers.edit');
    Route::put('/admin/drivers/{id}', [AdminDriverController::class, 'update'])->name('admin.drivers.update');
    Route::delete('/admin/drivers/{id}', [AdminDriverController::class, 'destroy'])->name('admin.drivers.destroy');
    
    // Admin ride CRUD
    Route::get('/admin/rides', [AdminRideController::class, 'index'])->name('admin.rides.index');
    Route::get('/admin/rides/create', [AdminRideController::class, 'create'])->name('admin.rides.create');
    Route::post('/admin/rides', [AdminRideController::class, 'store'])->name('admin.rides.store');
    Route::put('/admin/rides/{id}', [AdminRideController::class, 'update'])->name('admin.rides.update');
    Route::delete('/admin/rides/{id}', [AdminRideController::class, 'destroy'])->name('admin.rides.destroy');
    Route::get('/admin/rides/{id}/passengers', [AdminRideController::class, 'passengers'])->name('admin.rides.passengers');
    
    // Admin driver document CRUD
    Route::get('/admin/driver-documents', [AdminDriverDocumentController::class, 'index'])->name('admin.driver_documents.index');
    Route::get('/admin/driver-documents/{id}', [AdminDriverDocumentController::class, 'show'])->name('admin.driver_documents.show');
    Route::post('/admin/driver-documents/{id}/verify', [AdminDriverDocumentController::class, 'verify'])->name('admin.driver_documents.verify');
    Route::post('/admin/driver-documents/{id}/unverify', [AdminDriverDocumentController::class, 'unverify'])->name('admin.driver_documents.unverify');
    Route::get('/admin/driver-documents/create', [AdminDriverDocumentController::class, 'create'])->name('admin.driver_documents.create');
    Route::post('/admin/driver-documents', [AdminDriverDocumentController::class, 'store'])->name('admin.driver_documents.store');
    Route::get('/admin/driver-documents/{id}/edit', [AdminDriverDocumentController::class, 'edit'])->name('admin.driver_documents.edit');
    Route::put('/admin/driver-documents/{id}', [AdminDriverDocumentController::class, 'update'])->name('admin.driver_documents.update');
    Route::delete('/admin/driver-documents/{id}', [AdminDriverDocumentController::class, 'destroy'])->name('admin.driver_documents.destroy');
});

