<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\NotificationPreferenceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Instructor\ScheduleController;
use App\Http\Controllers\Instructor\CommunicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\SetPasswordController;
use Illuminate\Support\Facades\Auth;

// Define Auth routes manually instead of using Auth::routes(['verify' => true])
// TEMPORARILY DISABLED MIDDLEWARE: 'web'
Route::group([], function () {
    // Authentication Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Registration Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Email Verification Routes
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

// Public routes - accessible to anyone
Route::get('/', function () {
    // Always show welcome page to all users regardless of authentication status
    return view('welcome');
})->name('welcome');

// TEMPORARILY DISABLED MIDDLEWARE: 'auth', 'verified'
Route::group([], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/customer/home', function () {
        return view('customer.home');
    })->name('customer.home');
    
    // Booking routes
    Route::get('/booking', [BookingController::class, 'create'])->name('booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

// Customer specific home
Route::get('/customer/home', function () {
    return view('customer.home');
})->name('customer.home');

// User specific routes
Route::prefix('user')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('user.bookings');
    Route::get('/profile', function () {
        return view('user.profile');
    })->name('user.profile');
});

// Customer routes - TEMPORARILY DISABLED MIDDLEWARE: 'web', 'auth'
Route::prefix('customer')->name('customer.')->group(function () {
    // Reservation routes for customers - using the Customer namespace controller
    Route::get('/reservations', [App\Http\Controllers\Customer\ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [App\Http\Controllers\Customer\ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [App\Http\Controllers\Customer\ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [App\Http\Controllers\Customer\ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{reservation}/edit', [App\Http\Controllers\Customer\ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [App\Http\Controllers\Customer\ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [App\Http\Controllers\Customer\ReservationController::class, 'destroy'])->name('reservations.destroy');
    
    // Cancellation routes for customers
    Route::get('/reservations/{reservation}/cancel', [App\Http\Controllers\Customer\CancellationController::class, 'create'])->name('reservations.cancel');
    Route::post('/reservations/{reservation}/cancel', [App\Http\Controllers\Customer\CancellationController::class, 'store'])->name('reservations.cancel.store');
    Route::get('/reservations/{reservation}/reschedule', [App\Http\Controllers\Customer\CancellationController::class, 'reschedule'])->name('reservations.reschedule');
    Route::post('/reservations/{reservation}/reschedule', [App\Http\Controllers\Customer\CancellationController::class, 'storeReschedule'])->name('reservations.reschedule.store');
    
    // Customer profile routes
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
});

// Instructor routes - Note: middleware properly defined here
Route::prefix('instructor')->name('instructor.')->middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('instructor.dashboard');
    })->name('dashboard');
    
    Route::get('/students', function () {
        return view('instructor.students');
    })->name('students');
    
    Route::get('/schedule', function () {
        return view('instructor.schedule');
    })->name('schedule');
    
    // Schedule management for instructors
    Route::get('/schedule/daily', [ScheduleController::class, 'daily'])->name('schedule.daily');
    Route::get('/schedule/weekly', [ScheduleController::class, 'weekly'])->name('schedule.weekly');
    Route::get('/schedule/monthly', [ScheduleController::class, 'monthly'])->name('schedule.monthly');
    Route::get('/schedule/lesson/{reservation}', [ScheduleController::class, 'lessonDetails'])->name('schedule.lesson');
    
    // Communication routes
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/', [CommunicationController::class, 'index'])->name('index');
        Route::get('/illness/{reservation}', [CommunicationController::class, 'showIllnessForm'])->name('illness.form');
        Route::post('/illness/{reservation}', [CommunicationController::class, 'sendIllnessNotification'])->name('illness.send');
        Route::get('/weather/{reservation}', [CommunicationController::class, 'showWeatherForm'])->name('weather.form');
        Route::post('/weather/{reservation}', [CommunicationController::class, 'sendWeatherNotification'])->name('weather.send');
    });
});

// Admin routes 
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
    
    // Instructor management routes
    Route::get('/instructors', [App\Http\Controllers\Admin\InstructorController::class, 'index'])->name('instructors.index');
    Route::get('/instructors/create', [App\Http\Controllers\Admin\InstructorController::class, 'create'])->name('instructors.create');
    Route::post('/instructors', [App\Http\Controllers\Admin\InstructorController::class, 'store'])->name('instructors.store');
    Route::get('/instructors/{instructor}', [App\Http\Controllers\Admin\InstructorController::class, 'show'])->name('instructors.show');
    Route::get('/instructors/{instructor}/edit', [App\Http\Controllers\Admin\InstructorController::class, 'edit'])->name('instructors.edit');
    Route::put('/instructors/{instructor}', [App\Http\Controllers\Admin\InstructorController::class, 'update'])->name('instructors.update');
    Route::get('/instructors/{instructor}/confirm-delete', [App\Http\Controllers\Admin\InstructorController::class, 'confirmDelete'])->name('instructors.confirm-delete');
    Route::delete('/instructors/{instructor}', [App\Http\Controllers\Admin\InstructorController::class, 'destroy'])->name('instructors.destroy');
    Route::get('/instructors/{instructor}/schedule', [App\Http\Controllers\Admin\InstructorController::class, 'schedule'])->name('instructors.schedule');
    
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
    Route::get('/customers/{customer}/confirm-delete', [App\Http\Controllers\Admin\CustomerController::class, 'confirmDelete'])->name('customers.confirm-delete');
    Route::delete('/customers/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
    
    // Reservation management routes for admin
    Route::get('/reservations', [App\Http\Controllers\Admin\ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [App\Http\Controllers\Admin\ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [App\Http\Controllers\Admin\ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [App\Http\Controllers\Admin\ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{reservation}/edit', [App\Http\Controllers\Admin\ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [App\Http\Controllers\Admin\ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [App\Http\Controllers\Admin\ReservationController::class, 'destroy'])->name('reservations.destroy');
    
    // Cancellation management routes for admin
    Route::get('/cancellations', [App\Http\Controllers\Admin\CancellationController::class, 'index'])->name('cancellations.index');
    Route::get('/cancellations/{cancellation}', [App\Http\Controllers\Admin\CancellationController::class, 'show'])->name('cancellations.show');
    Route::patch('/cancellations/{cancellation}', [App\Http\Controllers\Admin\CancellationController::class, 'update'])->name('cancellations.update');
    
    // Communication routes for admin
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/illness/{reservation}', [App\Http\Controllers\Admin\CommunicationController::class, 'showIllnessForm'])->name('illness.form');
        Route::post('/illness/{reservation}', [App\Http\Controllers\Admin\CommunicationController::class, 'sendIllnessNotification'])->name('illness.send');
        Route::get('/weather/{reservation}', [App\Http\Controllers\Admin\CommunicationController::class, 'showWeatherForm'])->name('weather.form');
        Route::post('/weather/{reservation}', [App\Http\Controllers\Admin\CommunicationController::class, 'sendWeatherNotification'])->name('weather.send');
    });
    
    // Payment confirmation routes
    Route::get('/payments/{reservation}/confirm', [App\Http\Controllers\Admin\PaymentConfirmationController::class, 'showConfirmationForm'])->name('payments.form');
    Route::post('/payments/{reservation}/confirm', [App\Http\Controllers\Admin\PaymentConfirmationController::class, 'confirmPayment'])->name('payments.confirm');
    
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
});

// Profile routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// TEMPORARILY DISABLED MIDDLEWARE: 'auth'
Route::group([], function () {
    Route::get('/profile/notifications', [NotificationPreferenceController::class, 'edit'])->name('profile.notifications');
    Route::patch('/profile/notifications', [NotificationPreferenceController::class, 'update'])->name('profile.notifications.update');
});

// Email verification routes
Route::get('/verification-sent', function () {
    return view('auth.verification-sent');
})->name('verification.sent');

// BARE VERIFICATION ROUTE
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->name('verification.verify');

// Password setting routes
Route::get('/set-password', [App\Http\Controllers\Auth\SetPasswordController::class, 'showSetPasswordForm'])
    ->name('password.set.form');
    
Route::post('/set-password', [App\Http\Controllers\Auth\SetPasswordController::class, 'setPassword'])
    ->name('password.set');

// CSRF refresh route
Route::view('/csrf-refresh', 'csrf-refresh')->name('csrf.refresh');

// Auth routes
require __DIR__.'/auth.php';

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Registration routes outside of middleware group
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Debug route
Route::get('/debug-register', function() {
    return view('debug.register-test');
})->name('debug.register');
